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
class Status extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() { return "statuses"; }

    public static function getStatusIdByKey($key) { return Status::find()->where(['status_key' => $key])->one()->id; }

    public static function getActive() { return Status::getStatusIdByKey('active'); }

    public static function getPending() { return Status::getStatusIdByKey('pending'); }

    public static function getBlocked() { return Status::getStatusIdByKey('blocked'); }

    public static function getDeleted() { return Status::getStatusIdByKey('deleted'); }

    public static function getPrivate() { return Status::getStatusIdByKey('private'); }

    public static function getProcessed() { return Status::getStatusIdByKey('processed'); }
}