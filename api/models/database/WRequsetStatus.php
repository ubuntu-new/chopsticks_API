<?php
namespace api\models\database;

use yii\db\ActiveRecord;

/**
 * Entity Status
 *
 * @package api\models
 *
 * @property integer $id
 * @property string $status_key
 * @property string $status_name
 */
class WRequsetStatus extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() { return "request_statuses"; }

    public static function getDb() {
        return \Yii::$app->dbw;
    }


    public static function getStatusIdByKey($key) { return WRequsetStatus::find()->where(['status_key' => $key])->one()->id; }

    public static function getPending() { return WRequsetStatus::getStatusIdByKey('Pending'); }

    public static function getAccept() { return WRequsetStatus::getStatusIdByKey('Accept'); }

    public static function getRecieve() { return WRequsetStatus::getStatusIdByKey('Recieve'); }

    public static function getVoid() { return WRequsetStatus::getStatusIdByKey('Void'); }

    public static function getReject() { return WRequsetStatus::getStatusIdByKey('Reject'); }

}