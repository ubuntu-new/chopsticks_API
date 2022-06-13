<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $item_name
 * @property int|null $user_id
 * @property int|null $created_at

 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*[['filePath', 'modelName', 'urlAlias'], 'required'],*/
//            [['filePath', 'modelName', 'urlAlias'], 'safe'],
//            [['itemId', 'isMain'], 'integer'],
//            [['filePath', 'urlAlias'], 'string', 'max' => 400],
//            [['modelName'], 'string', 'max' => 150],
//            [['name'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
//            'id' => 'ID',
            'item_name ' => 'Item Name ',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }
}
