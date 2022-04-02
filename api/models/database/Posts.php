<?php

namespace api\models\database;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $post_author
 * @property string $post_content
 * @property string $post_title
 * @property string $post_excerpt
 * @property string $post_status
 * @property string $post_name
 * @property string $post_type
 * @property int $view
 * @property string $post_tags
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $image
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_author', 'view'], 'integer'],
            [['post_content', 'post_title', 'post_name'], 'required'],
            [['post_content', 'post_title', 'post_name', 'post_status'], 'string'],
            [['created_at', 'updated_at', 'id'], 'safe'],
            [['post_name'], 'string', 'max' => 200],
            [['post_type'], 'string', 'max' => 20],
            [['post_tags','image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'post_author' => Yii::t('app', 'Post Author'),
            'post_content' => Yii::t('app', 'Post Content'),
            'post_title' => Yii::t('app', 'Post Title'),
            'post_excerpt' => Yii::t('app', 'Post Excerpt'),
            'post_status' => Yii::t('app', 'Post Status'),
            'post_name' => Yii::t('app', 'Post Name'),
            'post_type' => Yii::t('app', 'Post Type'),
            'view' => Yii::t('app', 'View'),
            'post_tags' => Yii::t('app', 'Post Tags'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'image' => Yii::t('app', 'image'),
        ];
    }
}
