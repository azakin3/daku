<?php

namespace App\Presenters;

use Nette, App\Model;


/**
 * Homepage presenter.
 */
class ApiPresenter extends BasePresenter
{


    public function renderDefault()
    {
        $a = array(
            "api_version" => "v1",
            "status" => "ok",
            "documentation" => "http://edaku.eu/docs/api"



        );

        $this->template->json     = $a;
        $this->template->settings = array(
            JSON_PRETTY_PRINT,
            JSON_UNESCAPED_SLASHES
        );
    }



    public function renderItems()
    {

        $data        = $this->context->item->getAllItems()->fetchAll();
        $item_id     = array();
        $itemnames   = array();
        $description = array();
        $price       = array();
        $category    = array();

        for ($i = 0; $i < count($data); $i++) {
            $itemnames[]   = $data[$i]->item_name;
            $description[] = $data[$i]->description;
            $price[]       = $data[$i]->price;
            $item_id[]     = $data[$i]->item_id;
            $category[]    = $data[$i]->category_name;
        }

        $res = array();
        for ($i = 0; $i < count($description); $i++) {
            $res[] = array(
                "id" => $item_id[$i],
                "name" => $itemnames[$i],
                "description" => $description[$i],
                "price" => $price[$i],
                "category" => $category[$i]
            );
        }


        $a = array(
            "api_version" => "v1",
            "status" => "ok",
            "documentation" => "http://edaku.eu/docs/api",
            "items" => $res
        );

        $this->template->json = $a;
        //$this->sendResponse( new Nette\Application\Responses\JsonResponse( $a ) );
    }

    public function renderItem($id)
    {
        $data = $this->context->item->getDetail($id);
        if ($data) {
            $a                    = array(
                "api_version" => "v1",
                "status" => "ok",
                "documentation" => "http://edaku.eu/docs/api",
                "item_detail" => array(
                    "item_name" => $data->item_name,
                    "description" => $data->description,
                    "price" => $data->price,
                    "in_stock" => $data->in_stock

                )
            );
            $this->template->json = $a;
        } else {
            $this->template->json = array(
                "message" => "Not Found"
            );
        }
    }
    public function renderCategory($id)
    {
        $data = $this->context->category->getAll($id);
        if (count($data)) {

            if (count($data) > 1) {
                for ($i = 1; $i < count($data) + 1; $i++) {
                    $res[] = array(
                        "id" => $data[$i]->item_id,
                        "name" => $data[$i]->item_name,
                        "description" => $data[$i]->description,
                        "price" => $data[$i]->price
                    );
                }


                $a = array(
                    "api_version" => "v1",
                    "status" => "ok",
                    "documentation" => "http://edaku.eu/api",
                    "items" => $res
                );

                $this->template->json = $a;
            } else {
                $data  = $data->fetch();
                $res[] = array(
                    "id" => $data->item_id,
                    "name" => $data->item_name,
                    "description" => $data->description,
                    "price" => $data->price
                );

                $a = array(
                    "api_version" => "v1",
                    "status" => "ok",
                    "documentation" => "http://edaku.eu/api",
                    "items" => $res
                );
                $this->template->json = $a;
            }


        } else {



            $this->template->json = array(
                "message" => "Not Found"
            );
        }

    }



    public function RenderLogin($mail,$password){
        try {
            $this->getUser()->login($mail,$password);

        } catch (Nette\Security\AuthenticationException $e) {

        }
        if($this->getUser()->id){
            $logged="Logged In";

        $this->context->api->createApiKey($this->getUser()->id);

        }else{
            $logged="Authentication Failed";
        }

        $this->template->mail=$mail;

        $this->template->json = array(
            "message" => $logged
        );



    }



}
