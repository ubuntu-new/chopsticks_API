<?php
namespace api\models\response;


use api\models\database\SafeBalance;

class SafeResponse
{
    public $id,
        $amount,
        $default_amount,
        $branch,
        $branch_id,
        $status,
        $safe_balance,
        $created_at;



    public function __construct($row,$t) {
        $this->id = $row['id'];

        $this->amount = $row['amount'];
        $this->branch_id = $row['branch_id'];
        $this->default_amount = $row['default_amount'];
        $this->branch = $row['branch'];
        $this->status = $row['status'];
        $this->created_at = $row["created_at"];
        $this->safe_balance =  SafeBalance::find()->where(["safe_id"=>$row['id']])->andWhere(["like", 'created_at', $t."%", false])->all();


    }


}