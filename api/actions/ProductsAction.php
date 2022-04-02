<?php
namespace api\actions;
use api\models\database\Banks;
use api\models\database\Branches;
use api\models\database\CloseDay;
use api\models\database\Customers;
use api\models\database\DeliveryMethods;
use api\models\database\DriversBalance;
use api\models\database\DriversBalanceDetail;
use api\models\database\Ingredients;
use api\models\database\Orders;
use api\models\database\OrderStatus;
use api\models\database\PaymentMethods;
use api\models\database\Pcategory;
use api\models\database\Poses;
use api\models\database\PosesBalance;
use api\models\database\PosesBalanceDetail;
use api\models\database\PosesToCashier;
use api\models\database\Products;
use api\models\database\Products_images;
use api\models\database\Safe;
use api\models\database\SafeBalance;
use api\models\database\SafeBalanceDetail;
use api\models\database\Status;
use api\models\database\TestOrders;
use api\models\database\Timesheet;
use api\models\database\User;
use api\models\response\CustomerResponse;
use api\models\response\ProductsResponse;
use api\models\response\Result;

use api\models\response\SafeResponse;
use phpDocumentor\Reflection\Types\Self_;
use yii\base\Exception;
use yii\db\mssql\PDO;
use yii\helpers\Json;

class ProductsAction {

    public static function addProduct($product_id = null, $s = null, $m = null, $xl = null) {

        $product_image = Products_images::find()->where(["procts_id"=>$product_id])->one();
        if (!$product_image) {
            $product_image = new Products_images();
            $product_image->procts_id = $product_id;
            $product_image->s = $s;
            $product_image->m = $m;
            $product_image->xl = $xl;
            $product_image->visible = 1;

        } else {
            $product_image->s = $s;
            $product_image->m = $m;
            $product_image->xl = $xl;
            $product_image->visible = 1;
        }
            if ($product_image->save())
                return Result::SUCCESS;
            else return Result::FAILURE;


    }






}