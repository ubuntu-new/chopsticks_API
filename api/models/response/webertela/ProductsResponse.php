<?php
namespace api\models\weresponse;

class ProductsResponse
{

    public $id,
        $w_id,
        $category_id,
        $category_name,
        $name,
        $price,
        $weight,
        $class_name,
        $is_special,
        $created_at,
        $status,
        $web,
        $nutritional,
        $description,
        $is_sticks,
        $price_sale,
        $gallery,
        $m,
        $s,
        $xl,
        $is_promo,
	$url;


    public function __construct($row) {
        $this->id = $row['id'];
        $this->w_id = $row["w_id"];
        $this->category_id = $row["category_id"];
        $this->category_name = $row["category_name"];
        $this->name = $row["name"];
        $this->price = $row["price"];
        $this->weight = $row["weight"];
        $this->class_name = $row["class_name"];
        $this->is_special = $row["is_special"];
        $this->created_at = $row["created_at"];
        $this->status = $row["status"];
        $this->web = $row["web"];
        $this->nutritional = $row["nutritional"];
        $this->description = $row["description"];
        $this->price_sale = $row["price_sale"];
        $this->gallery = $row["gallery"];
        $this->m = $row['m'];
        $this->s= $row['s'];
        $this->xl= $row['xl'];
        $this->is_promo = $row["is-promo"];
	$this->url = $row["url"];



    }
}

