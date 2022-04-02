<?php
namespace rest\modules\v1\controllers;

use api\actions\ProductsAction;
USE api\actions\ProductssiteAction;
use api\actions\UserAction;
use api\models\database\IngredientsPrice;
use api\models\response\Result;
use rest\controllers\RestController;
use yii\base\Security;
use rest\models\response\Response;

class ProductsController extends RestController  {



    // get products list
    public function actionGetProducts() {

        return UserAction::getProducts();
    }

    // get products list from site
    public function actionGetProductssite() {

        return UserAction::getProductssite();
    }

    // get products category list
    public function actionGetProductsCategory() {

        return UserAction::getProductsCategory();
    }



    // get ingredients list
    public function actionGetIngredients() {
        $product_category = 0;
        if (\Yii::$app->request->post("product_category"))
            $product_category = \Yii::$app->request->post("product_category");
        return UserAction::getIngredients($product_category);
    }

      public function actionGetRecieptByProductId() {
          $product_id = trim(\Yii::$app->request->post('product_id'));

          $response = new Response();
          if (!$product_id) {
            $response->error_message = "Missing parameter: 'product_id'";
            return $response;
        }
          return UserAction::getReceiptByProductId($_POST["product_id"]);
      }

    public function actionSendOrder() {
        $order =  \Opis\Closure\serialize(\Yii::$app->request->post('order'));
        return UserAction::recieveOrderPos($order,"pos");
    }

    public function actionGetIngredientsPrice() {
        return IngredientsPrice::find()->all();
    }

    public function actionGetOrderId() {
        return UserAction::getOrderId("digomi");
    }


    public function actionUploadFile() {

        $dir = \Yii::getAlias('@jobsstaff/web/uploads/chat/').$_POST['theme_id'];
        $small_img = null;
        if (!is_dir($dir))
            mkdir($dir);

        $dir2 = $dir."/".\Yii::$app->user->getId();

        if (!is_dir($dir2))
            mkdir($dir2);

        $result = [];


            if (isset($_FILES['chatFile'])) {
                $security = new Security();
                $extension = pathinfo($_FILES['chatFile']['name'], PATHINFO_EXTENSION);
                $randomString = $security->generateRandomString();
                if ($extension == "jpg" || $extension == "JPG" || $extension == "gif" || $extension == "JPEG" ||$extension == "jpeg" ||  $extension == "png" ) {
                    $small_img = $randomString."_small".".".$extension;
                    Image::thumbnail($_FILES['chatFile']['tmp_name'], 50, 50)->save($dir2.'/'.$randomString."_small.".$extension);
                }

                if (move_uploaded_file($_FILES['chatFile']['tmp_name'], $dir2.'/'.$randomString.".".$extension))


                    $result = ["filename"=>$randomString.".".$extension, "smallImg" => $small_img, "filesize"=>$_FILES['chatFile']['size'], "orig_filename"=>$_FILES['chatFile']['name']];
            }

        return $result;
    }


    public function actionAddImage() {
        $product_id = trim(\Yii::$app->request->post('product_id'));
        $s = trim(\Yii::$app->request->post('s'));
        $m = trim(\Yii::$app->request->post('m'));
        $xl = trim(\Yii::$app->request->post('xl'));

        $response = new Response();
        if (!$product_id) {
            $response->error_message = "Missing parameter: 'product_id'";
            return $response;
        }
        return ProductsAction::addProduct($product_id, $s, $m, $xl);
    }


}