<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "about".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $status
 * @property string|null $description
 * @property string $created
 * @property string|null $title_ge
 * @property string|null $description_ge
 * @property string|null $title_ru
 * @property string|null $description_ru
 */
class About extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'about';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'description_ge', 'description_ru','title', 'title_ge', 'title_ru'], 'required'],
            [['description', 'description_ge', 'description_ru'], 'string'],
            [['created','status'], 'safe'],
            [['title', 'title_ge', 'title_ru'], 'string', 'max' => 255],
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
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
            'created' => Yii::t('app', 'Created'),
            'title_ge' => Yii::t('app', 'Title Ge'),
            'description_ge' => Yii::t('app', 'Description Ge'),
            'title_ru' => Yii::t('app', 'Title Ru'),
            'description_ru' => Yii::t('app', 'Description Ru'),
        ];
    }
}
