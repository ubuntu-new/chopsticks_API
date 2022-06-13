<?php
namespace api\models\response;

class OrdersResponse
{

    public $id,
        $order_id,
        $user_id,
        $branch,
        $duration,
        $backer_id,
        $source,
        $status,
        $driver_name,
        $order_data,
        $created_at,
        $finish_date;


    public function __construct($row) {
        $this->id = $row['id'];
        $this->order_id = $row["order_id"];
        $this->filePath= $row['filePath'];
        $this->user_id = $row["user_id"];
        $this->branch = $row["branch"];
        $this->duration = $row["duration"];
        $this->backer_id = $row["backer_id"];
        $this->source = $row["source"];
        $this->status = $row["status"];
        $this->driver_name = $row["driver_name"];
        $this->order_data = json_decode($row["order_data"]);
        $this->finish_date = $row['finish_date'];
        $this->created_at = $row['created_at'];
    }
}