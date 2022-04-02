<?php

namespace api\models\database;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $procts_id
 * @property string $s
 * @property string $m
 * @property string $xl
 * @property int $visible




 */
class Products_images extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_images';
    }

}
