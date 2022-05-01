<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "contactinfo".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $title_ge
 * @property string $description_ge
 * @property string $address
 * @property string $email
 * @property string $mob
 * @property string $facebook
 * @property string $instagramm
 * @property string $twitter
 */
class Contactinfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contactinfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title',], 'required'],
            [['title', 'title_ge', 'description_ge', 'address', 'email', 'mob', 'facebook', 'instagramm', 'twitter'], 'safe'],
            [['description', 'description_ge'], 'string'],
            [['title', 'title_ge', 'address', 'email', 'mob', 'facebook', 'instagramm', 'twitter'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'title_ge' => Yii::t('app', 'Title Ge'),
            'description_ge' => Yii::t('app', 'Description Ge'),
            'address' => Yii::t('app', 'Address'),
            'email' => Yii::t('app', 'Email'),
            'mob' => Yii::t('app', 'Mob'),
            'facebook' => Yii::t('app', 'Facebook'),
            'instagramm' => Yii::t('app', 'Instagramm'),
            'twitter' => Yii::t('app', 'Twitter'),
        ];
    }
}
