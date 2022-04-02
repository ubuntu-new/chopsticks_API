<?php
namespace api\models\response;


class RecipesResponse
{
    public
        $id,
        $product_id,
        $parent,
        $child,
        $unit,
        $qty,
        $small,
        $large,
        $recipe_result_min,
        $recipes_result_max,
        $created,
        $visible;



    public function __construct($row) {
        $this->id = $row['id'];
        $this->product_id = $row['product_id'];
        $this->parent = $row['parent'];
        $this->child = $row['child'];
        $this->unit = $row['unit'];
        $this->qty = $row['qty'];
        $this->small = $row['small'];
        $this->large = $row['large'];
        $this->recipe_result_min = $row['recipe_result_min'];
        $this->recipes_result_max = $row['recipes_result_max'];
        $this->created = $row['created'];
        $this->visible = $row['visible'];

    }


}