<?php
namespace api\actions;
use api\models\database\Customers;
use api\models\database\Ingredients;
use api\models\database\Orders;
use api\models\database\Pcategory;
use api\models\database\Products;
use api\models\database\Status;
use api\models\database\TestOrders;
use api\models\database\User;
use api\models\response\CustomerResponse;
use api\models\response\ProductsResponse;
use api\models\response\Result;

use yii\base\Exception;
use yii\helpers\Json;

class CustomersAction {

    public static function createCustomer($id = null, $name = null, $address = null, $phone = null, $comment = null, $comment2 = null, $b_day = null, $gender = null, $email = null, $discount = null,  $personal_id = null, $ltd_id = null, $ltd_name = null) {


     $customer =  Customers::find()->andFilterWhere(['id'=>$id])->one();
        if ($customer) {
            $customer->tel = $phone;
            $customer->name = $name;
            $customer->address  = $address;
            $customer->discount  = $discount;
            $customer->comment = $comment;
            $customer->comment2 = $comment2;
            $customer->personal_id = $personal_id;
            $customer->ltd_name = $ltd_name;
            $customer->ltd_id = $ltd_id;
            $customer->b_day = $b_day;
            $customer->email = $email;
            $customer->gender = $gender;
            return $customer->save()?true:false;
        }
        else {
            $customer = new Customers();
            if (!$name || !$phone)
                return false;
            $customer->tel = $phone;
            $customer->name = $name;
            $customer->discount = $discount;
            $customer->personal_id = $personal_id;
            $customer->ltd_name = $ltd_name;
            $customer->ltd_id = $ltd_id;
            $customer->address  = $address;
            $customer->comment = $comment;
            $customer->comment2 = $comment2;
            $customer->b_day = $b_day;
            $customer->email = $email;
            $customer->gender = $gender;
            return $customer->save()?true:false;
        }




    }

    public static function findCustomer($phone = null) {
        if (!$phone)
            return false;
        else  return    Customers::find()->andFilterWhere(['like', 'tel', "%$phone%", false])->all();
    }

    public static function findCustomerJson($phone = null) {
        if (!$phone)
            return false;
        $customers = Customers::find()->andFilterWhere(['like', 'tel', "%$phone%", false])->all();
        $result = [];
        foreach ($customers as $customer) {
            $result[] = new CustomerResponse($customer);
        }
        return $result;
    }

    public static function getLastOrder($phone = null) {
        $orders = Orders::find()->where(['like',"order_data","$phone"])->andWhere(["source"=>"Pos"])->orderBy(['id' => SORT_DESC])->one();
        return $orders? Json::decode($orders->order_data): null;
    }

    public static function getAllOrders($phone = null) {
        $orders = Orders::find()->where(['like',"order_data","$phone"])->orderBy(['id' => SORT_DESC])->all();
        return $orders;
    }


}