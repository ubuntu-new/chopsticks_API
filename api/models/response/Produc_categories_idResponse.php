<?php
namespace api\models\response;


class Produc_categories_idResponse
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