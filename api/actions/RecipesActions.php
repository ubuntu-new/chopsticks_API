<?php
namespace api\actions;


use api\models\response\RecipesResponse;
use api\models\database\Recipes;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class RecipesActions {
    public static function getList(){

        return Recipes::find()->all();
    }

    public static function getId($product_id = null){
//        return $product_id;
        $recipes = Recipes::find()->where(['product_id' => $product_id])->all();
//        $recipes =  Recipes::find()->where(["id"=>$id])->one();
        return $recipes;
    }

    public static function createOld($product_id,$child_product_id,$parent,$child,$unit,$qty,$small,$large,$recipe_result_min,$recipes_result_max){

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $recipes  = new recipes();
            $recipes->product_id = $product_id;
            $recipes->child_product_id = $child_product_id;
            $recipes->parent = $parent;
            $recipes->child = $child;
            $recipes->unit = $unit;
            $recipes->qty = $qty;
            $recipes->small = $small;
            $recipes->large = $large;
            $recipes->recipe_result_min = $recipe_result_min;
            $recipes->recipes_result_max = $recipes_result_max;
            $recipes->save();

            $transaction->commit();
            return Result::SUCCESS;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function create($product_id = null,$datas = null,$child_product_id = null,$parent = null){

        $transaction = \Yii::$app->db->beginTransaction();

        try {

            foreach ($datas as $row)
            {
                $recipes  = new recipes();
                $recipes->product_id = $product_id;
                $recipes->child_product_id = $child_product_id;
                $recipes->parent = $parent;
                $recipes->save();
            }



            $transaction->commit();
            return Result::SUCCESS;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

}