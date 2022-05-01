<?php
namespace api\models\weresponse;

class ContactinfoResponse
{

    public $id,
        $title,
        $description,
        $title_ge,
        $description_ge,
        $address,
        $email,
        $mob,
        $facebook,
        $instagramm,
        $twitter;


    public function __construct($row) {
        $this->id = $row['id'];
        $this->title = $row["title"];
        $this->description = $row["description"];
        $this->title_ge = $row["title_ge"];
        $this->description_ge = $row["description_ge"];
        $this->address = $row["address"];
        $this->email = $row["email"];
        $this->mob = $row["mob"];
        $this->facebook = $row["facebook"];
        $this->instagramm = $row["instagramm"];
        $this->twitter = $row["twitter"];

    }
}
