<?php

namespace api\models\database;


use app\models\Pcategory;
use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "PosesBalanceDetail".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property integer $created_at
 * @property integer $updated_at




 */
class Wunit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit';
    }
    public static function getDb() {
        return \Yii::$app->dbw;
    }

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
}
