<?php
namespace api\models\response;


use api\actions\CustomersAction;


class SiteCustomerResponse
{
    public
        $id,
        $tel,
        $name,
        $address,
        $discount,
        $gender,
        $comment,
        $comment2,
        $personal_id,
        $ltd_name,
        $ltd_id,
        $b_day,
        $updated_at,
        $created_at,
        $email;



    public function __construct($row) {
        $this->id = $row['id'];
        $this->tel = $row['tel'];
        $this->name = $row["name"];
        $this->discount = $row["discount"];
        $this->gender = $row["gender"];
        $this->phone = \Opis\Closure\unserialize($row['tel']);
        $this->address = \Opis\Closure\unserialize($row["address"]);
        $this->invoice = \Opis\Closure\unserialize($row["invoice"]);
        $this->comment = $row['comment'];
        $this->comment2 = $row['comment2'];
        $this->personal_id = $row['personal_id'];
        $this->ltd_id = $row['ltd_id'];
        $this->ltd_name = $row['ltd_name'];
        $this->updated_at = $row['updated_at'];
        $this->created_at = $row['created_at'];
    //    $this->last_order = json_decode(CustomersAction::getLastOrder($this->phone));
    }


}