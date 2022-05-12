<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $filePath
 * @property int|null $itemId
 * @property int|null $isMain
 * @property string $modelName
 * @property string $urlAlias
 * @property string|null $name
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*[['filePath', 'modelName', 'urlAlias'], 'required'],*/
            [['filePath', 'modelName', 'urlAlias'], 'safe'],
            [['itemId', 'isMain'], 'integer'],
            [['filePath', 'urlAlias'], 'string', 'max' => 400],
            [['modelName'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filePath' => 'File Path',
            'itemId' => 'Item ID',
            'isMain' => 'Is Main',
            'modelName' => 'Model Name',
            'urlAlias' => 'Url Alias',
            'name' => 'Name',
        ];
    }
}
