<?php
namespace api\actions;


use api\models\response\BranchesResponse;
use api\models\response\RecipesResponse;
use api\models\database\Branches;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class BranchesAction {
    public static function getList(){

//        return Branches::find()->all();
        $result = [];

        $sql = "SELECT {{p}}.*,{{a}}.[[address]],{{a}}.[[maps]],{{h}}.[[working_days]] FROM {{branches}} {{p}}
                    LEFT JOIN {{branches_address}} {{a}} ON {{a}}.[[branches_id]] = [[p]].[[id]]
                    LEFT JOIN {{branches_working_hours}} {{h}} ON {{h}}.[[branches_id]] = {{p}}.[[id]] 
                         WHERE {{p}}.[[status]] = 1 
                               ORDER BY {{p}}.[[id]] ASC ";



        $branches = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);




        foreach ($branches as $row) {
            $result[] = new BranchesResponse($row);
        }

        return $result;

    }


//    public static function create($product_id = null, $datas = null, $recipe_result_min = null){
//
//        $transaction = \Yii::$app->db->beginTransaction();
//
//        try {
//
//            foreach ($datas as $row)
//            {
//
//                $recipes  = new recipes();
//                $recipes->product_id = $product_id;
//                $recipes->child_product_id = $row["product_id"];
//                $recipes->qty = $row["qty"];
//                $recipes->unit = $row["unit"];
//                $recipes->recipe_result_min = $row["recipe_result_min"];
//                $recipes->recipes_result_max = $row["recipe_result_max"];
// //               $recipes->parent = $parent;
//                $recipes->save();
//            }
//
//
//            $transaction->commit();
//            return Result::SUCCESS;
//        } catch (\yii\db\Exception $ex) {
//            $transaction->rollBack();
//            \Yii::error($ex->getMessage());
//        }
//        return Result::FAILURE;
//
//    }

}