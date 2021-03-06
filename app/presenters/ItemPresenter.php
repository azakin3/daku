<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Utils\Strings;


/**
 * Homepage presenter.
 */
class ItemPresenter extends BasePresenter
{


    public function renderDefault($item,  $itemname)
    {
        $data=$this->context->item->getDetail($item);
        $this->template->items = $data;
        if($this->getUser()->isLoggedIn()){
            $this->template->logged = true;
        }else{
            $this->template->logged = false;
        }

    }

    public function renderBuy($id,$itemname,$q)
    {
        if (isset($id,$itemname,$q)) {

            if (!$this->getUser()->isLoggedIn()) {
                $this->flashMessage('Musíte se přihlásit', 'success');
                $this->redirect('Item:default', $id, Strings::webalize($itemname));
            } else {

                $check = $this->context->item->descItem($id,$q);

                if($check){
                    $this->context->item->buyItem($id, $this->context->cartsession->getCart(), $q);
                }else{
                    $this->flashMessage('Něco se stalo :(', 'error');
                    $this->redirect('Item:default', $id, Strings::webalize($itemname));
                }




                $this->flashMessage('Děkujeme za nákup', 'success');
                $this->redirect('Item:default#zakoupeno', $id, Strings::webalize($itemname));
            }
        }else{
            $this->flashMessage('Něco se stalo :(', 'error');
            $this->redirect('Main:');
        }
    }

    public function renderRemove($id,$item_id){
        if (!$this->getUser()->isLoggedIn()) {
            $this->error('Musíte se přihlásit');
        }else{

            $this->context->item->removeItem($id,$this->context->cartsession->getCart(),$item_id);
            $this->redirect("Cart:default");

            //$this->flashMessage('Děkujeme za nákup', 'success');
            //$this->redirect('Item:default',$id,"as");
        }
    }

}
