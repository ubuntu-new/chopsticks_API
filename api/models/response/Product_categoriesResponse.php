<?php
namespace api\models\response;


class Product_categoriesResponse
{
    public
        $id,
        $name,
        $w_id,
        $weight;


    public function __construct($row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->w_id = $row['w_id'];
        $this->weight = $row['weight'];
    }

}