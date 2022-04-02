<?php

namespace api\models\database;


use app\models\Pcategory;
use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $poses_id
 * @property double $cash
 * @property double $card
 * @property double $glovo
 * @property double $glovo_cash
 * @property double $glovo_card
 * @property double $wolt_card
 * @property string $branch_name
 * @property string $created_at
 * @property string $start_time
 * @property string $end_time




 */
class PosesBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poses_balance';
    }
}
