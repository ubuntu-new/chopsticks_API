<?php
namespace api\models\response;


class PostsSiteResponse
{
    public $id,
        $post_author,
        $post_content,
        $post_title,
        $post_excerpt,
        $post_status,
        $post_name,
        $post_type,
        $view,
        $post_tags,
        $created_at,
        $updated_at,
        $image;


    public function __construct($row) {
        $this->id = $row['id'];
        $this->post_author = $row['post_author'];
        $this->post_content = $row["post_content"];
        $this->post_title = $row["post_title"];
        $this->post_excerpt = $row["post_excerpt"];
        $this->post_status = $row["post_status"];
        $this->post_name = $row["post_name"];
        $this->post_type = $row["post_type"];
        $this->view = $row["view"];
        $this->post_tags = $row["post_tags"];
        $this->created_at = $row["created_at"];
        $this->updated_at = $row["updated_at"];
        $this->image = $row["image"];


    }
}

