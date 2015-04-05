<?php

namespace App\Presenters;

use Nette,
    App\Model;


/**
 * Homepage presenter.
 */
class ApiPresenter extends BasePresenter
{


    public function renderDefault()
    {
        $a = array( "api_version" => "v1", "app" => "daku", "api_urls" =>
            array("items_all"=> "items/{page}", "item_detail"=>"/item/{id}", "item_buy"=>"/{user_key}/buy/{id}"


            ));

        $this->template->json=$a;
        $this->template->settings=array(JSON_PRETTY_PRINT,JSON_UNESCAPED_SLASHES);
    }

    public function renderItems()
    {

        $data=$this->context->item->getAllItems();
        $item_id=array();
        $itemnames=array();
        $description=array();
        $price=array();
        $category=array();

        for($i=1;$i<count($data);$i++){
            $itemnames[]=$data[$i]->item_name;
            $description[]=$data[$i]->description;
            $price[]=$data[$i]->price;
            $item_id[]=$data[$i]->item_id;
            $category[]=$data[$i]->category;
        }

        $res=array();
        for($i=0;$i<count($description);$i++){
            $res[]=array("id"=>$item_id[$i],"name"=>$itemnames[$i], "description"=>$description[$i], "price"=>$price[$i],
                "category"=>$category[$i]);
        }


        $a = array("current_url"=>"http://api.edaku.eu/items", "status"=>"ok","items"=>$res);

        $this->template->json=$a;
        //$this->sendResponse( new Nette\Application\Responses\JsonResponse( $a ) );
    }

    public function renderItem($id)
    {
        $data=$this->context->item->getDetail($id);
        if($data) {
            $a = array("current_url" => "api.edaku.eu/item/", "item_detail" => array("item_name" => $data->item_name,
                "description" => $data->description, "price" => $data->price, "in_stock" => $data->in_stock, "category" => $data->category));
            $this->template->json = $a;
        }else{
            $this->template->json = array("message"=>"Not Found");
        }
    }
    public function renderCategory($id)
    {
        $data=$this->context->category->getAll($id);
        if(count($data)){

        if(count($data) > 1) {
            for ($i = 1; $i < count($data) + 1; $i++) {
                $res[] = array("id" => $data[$i]->item_id, "name" => $data[$i]->item_name, "description" => $data[$i]->description, "price" => $data[$i]->price,
                );
            }


            $a = array("current_url" => "http://api.edaku.eu/items", "status" => "ok", "items" => $res);

            $this->template->json = $a;
        } else {
            $data = $data->fetch();
            $res[] = array("id" => $data->item_id, "name" => $data->item_name, "description" => $data->description, "price" => $data->price,
            );

            $a = array("current_url" => "http://api.edaku.eu/items", "status" => "ok", "items" => $res);
            $this->template->json = $a;
        }


        }else{



            $this->template->json = array("message" => "asd");
    }

    }




}
