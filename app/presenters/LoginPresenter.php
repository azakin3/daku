<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;


/**
 * Homepage presenter.
 */
class LoginPresenter extends BasePresenter
{


    public function renderDefault($item)
    {
        $this->template->item = $item;

    }

    protected function createComponentPrihlaseniForm()
    {
        $form = new Form;

        $form->addText('name', 'E-mail')
            ->setRequired("Jméno je povinné");


        $form->addPassword('password', 'Heslo:')->setRequired("Heslo je povinne");


        $form->addSubmit('send', 'Login');
        $form->onSuccess[] = array($this, 'commentFormSucceeded');
        return $form;
    }


    public function commentFormSucceeded($form, $values)
    {
        $this->template->name=$values->name;
        $this->template->pass=$values->password;


        //$this->redirect('Main:default');

        //$this->flashMessage('Děkuji za komentář', 'success');
        //$this->redirect('this');
    }

}
