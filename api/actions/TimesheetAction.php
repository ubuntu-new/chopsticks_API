<?php
namespace api\actions;

use api\models\database\Timesheet;
use api\models\response\CustomerResponse;
use api\models\response\Result;
use Cassandra\Time;


class TimesheetAction {

    public static function start() {

        $user_id = \Yii::$app->user->getId();

        $timesheet = Timesheet::find()->where(["user_id"=>$user_id])->andWhere(["state"=>"IN"])
            ->andWhere(["end_date"=>""])->one();
        if (!$timesheet) {
            $timesheet =  new Timesheet();
            $timesheet->user_id = $user_id;
            $timesheet->state = "IN";
            $timesheet->start_date = time();
            if ($timesheet->save())
                return time();
            else return false;

        } else return "User is already IN state";
    }
    public static function startBreak() {

        $user_id = \Yii::$app->user->getId();

        $timesheet = Timesheet::find()->where(["user_id"=>$user_id])->andWhere(["state"=>"IN"])->andWhere(["end_date"=>""])->orderBy(['id' => SORT_DESC])->one();
        $timesheet->end_date = time();
        $timesheet->save();


        $timesheet =  new Timesheet();
        $timesheet->user_id = $user_id;
        $timesheet->state = "BREAK";
        $timesheet->start_date = time();
        if ($timesheet->save())
            return time();
        else return false;
    }

    public static function endBreak() {

        $user_id = \Yii::$app->user->getId();

        $timesheet = Timesheet::find()->where(["user_id"=>$user_id])->andWhere(["state"=>"BREAK"])->andWhere(["end_date"=>""])->orderBy(['id' => SORT_DESC])->one();
        $timesheet->end_date = time();
        $timesheet->save();


        $timesheet =  new Timesheet();
        $timesheet->user_id = $user_id;
        $timesheet->state = "IN";
        $timesheet->start_date = time();
        if ($timesheet->save())
            return time();
        else return false;
    }

    public static function finish() {

        $user_id = \Yii::$app->user->getId();

        $timesheet = Timesheet::find()->where(["user_id"=>$user_id])->andWhere(["end_date"=>""])->orderBy(['id' => SORT_DESC])->one();
        $timesheet->end_date = time();
        $timesheet->save();


        $timesheet =  new Timesheet();
        $timesheet->user_id = $user_id;
        $timesheet->state = "FINISH";
        $timesheet->start_date = time();
        if ($timesheet->save())
            return time();
        else return false;
    }

    public static function getActiveUsers() {
        $time = strtotime(date('Y-m-d'));
        $sql  = "SELECT * FROM timesheet t 
            left join user u ON u.id = t.user_id 
         WHERE  t.state = 'IN' and t.start_date >= :start_date";
        $rows = \Yii::$app->db->createCommand($sql)
            ->bindValue(":start_date", $time)
            ->queryAll(\PDO::FETCH_ASSOC);

        return $rows;
    }

    public static function detailTimesheet($start_day = null,$end_day = null, $user_id = null) {


        return Timesheet::find()->where([">","created_at", $start_day." 00:00:00"])->andWhere(["<","created_at",$end_day." 23:59:59"])->andWhere(["user_id"=>$user_id])->all();
    }

}