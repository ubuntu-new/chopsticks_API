<?php
namespace api\actions;


use api\models\response\PostsSiteResponse;
use api\models\database\Posts;
use app\models\Post;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;
use Yii;



class PostsSiteAction {
    public static function getList($user_id = null){

        $result = [];
        $sql = "SELECT * FROM posts WHERE post_status = 'publish'";
        $posts_site = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);

        foreach ($posts_site as $row) {
            $result[] = new PostsSiteResponse($row);
        }
        return $result;
    }

    public static function createPost($id = null, $post_author = null, $post_content = null, $post_title = null, $post_excerpt = null, $post_status = null, $post_name = null, $post_type = null, $view = null, $image = null) {


        $post =  Posts::find()->andFilterWhere(['id'=>$id])->one();

        if ($post) {
            $post->post_author = $post_author;
            $post->post_content = $post_content;
            $post->post_title = $post_title;
            $post->post_excerpt = $post_excerpt;
            $post->post_status = $post_status;
            $post->post_name = $post_name;
            $post->post_type = $post_type;
            $post->image = $image;
            $post->view = $view;

            return $post->save()?true:false;
        }
        else {
            $post = new Posts();
            if (!$post_title || !$post_content)
                return false;
            $post->post_author = $post_author;
            $post->post_content = $post_content;
            $post->post_title = $post_title;
            $post->post_excerpt = $post_excerpt;
            $post->post_status = $post_status;
            $post->post_name = $post_name;
            $post->post_type = $post_type;
            $post->view = $view;

            return $post->save()?true:false;
        }




    }

}