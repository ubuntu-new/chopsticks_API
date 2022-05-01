<?php
namespace api\models\response;


class ProductssiteResponse
{
    public $id,
        $w_id,
        $category_id,
        $category_name,
        $name,
        $name_ge,
        $name_ru,
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
        $description_ge,
        $description_ru,
        $web;



    public function __construct($row) {
        $this->id = $row['id'];
        $this->w_id = $row['w_id'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->is_special = $row['is_special'];
        $this->is_sticks = $row['is_sticks'];
        $this->name = $row['name'];
        $this->name_ge = $row['name_ge'];
        $this->name_ru = $row['name_ru'];
        $this->m = $row['m'];
        $this->s= $row['s'];
        $this->xl= $row['xl'];
        $this->price = $row["category_id"]==3?-2:$row['price'];
        $this->priceBySizes = $row["category_id"]==3?\Opis\Closure\unserialize($row['price']):$row['price'];
        $this->class_name = $row['class_name'];
        $this->created_at = $row['created_at'];
        $this->nutritional = $row['nutritional'];
        $this->description = $row['description'];
        $this->description_ge = $row['description_ge'];
        $this->description_ru = $row['description_ru'];
        $this->web = $row['web'];

    }


}