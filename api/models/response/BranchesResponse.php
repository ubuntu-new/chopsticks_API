<?php
namespace api\models\response;


class BranchesResponse
{
    public
        $id,
        $name,
        $status,
        $address,
        $maps,
        $working_days;



    public function __construct($row) {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->status = $row['status'];
        $this->address = $row['address'];
        $this->maps = $row['maps'];
        $this->working_days = $row['working_days'];

    }
}