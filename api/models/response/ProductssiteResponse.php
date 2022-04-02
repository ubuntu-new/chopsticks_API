<?php
namespace api\models\response;


class ProductssiteResponse
{
    public $id,
        $w_id,
        $category_id,
        $category_name,
        $name,
        $priceBySizes,
        $class_name,
        $m,
        $s,
        $xl,
        $is_special,
        $is_sticks,
        $created_at,
        $nutritional,
        $description,
        $web;



    public function __construct($row) {
        $this->id = $row['id'];
        $this->w_id = $row['w_id'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->is_special = $row['is_special'];
        $this->is_sticks = $row['is_sticks'];
        $this->name = $row['name'];
        $this->m = $row['m'];
        $this->s= $row['s'];
        $this->xl= $row['xl'];
        $this->price = $row["category_id"]==3?-2:$row['price'];
        $this->priceBySizes = $row["category_id"]==3?\Opis\Closure\unserialize($row['price']):$row['price'];
        $this->class_name = $row['class_name'];
        $this->created_at = $row['created_at'];
        $this->nutritional = $row['nutritional'];
        $this->description = $row['description'];
        $this->web = $row['web'];

    }


}