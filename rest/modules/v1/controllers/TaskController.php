<?php
namespace rest\modules\v1\controllers;

use api\actions\OrgPostAction;
use api\actions\TasksAction;
use api\actions\TasksForApiAction;
use api\models\database\tasks\TasksHeader;
use api\models\response\Result;
use rest\controllers\RestController;
use rest\models\response\Response;
use rest\modules\v1\models\task\TaskModel;
use rest\modules\v1\models\task\VoiceTaskModel;
use yii\base\Exception;
use yii\web\BadRequestHttpException;

class TaskController extends RestController {

    public function actionGetDutyTasksByDate() {
        $user_id = \Yii::$app->request->get('user_id');
        $date = \Yii::$app->request->get('date');
        $current = (\Yii::$app->request->get('current') == 1) ? true : false;
        $program = \Yii::$app->request->get('program');
        $start = ($date) ? strtotime($date.' '.'00:00:00') : null;
        $end = ($date) ? strtotime($date.' '.'23:59:59') : null;

        $response = new Response();
        $response->is_error = false;
        $response->data = TasksAction::getDutyTasksByDateApi($user_id, $start, $end, $current, $program);
        return $response;
    }

    public function actionGetDutyTasksStat() {
        $user_id = \Yii::$app->request->get('user_id');
        $year = \Yii::$app->request->get('year');
        $month = \Yii::$app->request->get('month');
        $program = \Yii::$app->request->get('program');

        $response = new Response();
        $response->is_error = false;
        $response->data = TasksAction::getDutyTasksStat($user_id, $year, $month, $program);
        return $response;
    }

    public function actionGetUserDutyTasksStat() {
        $user_id = \Yii::$app->request->get('user_id');
        $program = \Yii::$app->request->get('program');
        $response = new Response();

        if (!$user_id) {
            $response->error_message = "Missing parameter: 'user_id'";
            return $response;
        }

        if (!$program) {
            $response->error_message = "Missing parameter: 'program'";
            return $response;
        }

        $response->is_error = false;
        $response->data = TasksAction::getUsersDutyTasksStat($user_id, $program);
        return $response;
    }

    public function actionAddTask() {
        $response = new Response();

        $model = new TaskModel();
        $model->setAttributes(\Yii::$app->request->post());

        if ($model->validate(['name', 'description', 'from', 'to', 'duration', 'repeat_type', 'priority_type', 'notify_emp', 'notify_head',
            'users', 'duty_id', 'program', 'files'])) {
            $result = $model->save();
            switch($result) {
                case 0:
                    $response->is_error = false;
                    break;
                case -1:
                    $response->is_error = true;
                    $response->error_message = \Yii::t('Notifications', 'Permission denied');
                    break;
                default:
                    $response->is_error = true;
                    $response->error_message = \Yii::t('Notifications', 'Operation failed '.$result);
            }
        } else {
            $first_error_key = array_keys($model->errors)[0];
            $response->error_message = $model->getFirstError($first_error_key);
        }
        return $response;
    }

    public function actionAddVoiceTask() {
        $response = new Response();

        $model = new VoiceTaskModel();
        $model->setAttributes(\Yii::$app->request->post());

        if ($model->validate()) {
            $result = $model->save();
            switch($result) {
                case 0:
                    $response->is_error = false;
                    break;
                case -1:
                    $response->is_error = true;
                    $response->error_message = \Yii::t('Notifications', 'Permission denied');
                    break;
                default:
                    $response->is_error = true;
                    $response->error_message = \Yii::t('Notifications', 'Operation failed');
            }
        } else {
            $first_error_key = array_keys($model->errors)[0];
            $response->error_message = $model->getFirstError($first_error_key);
        }
        return $response;
    }

    public function actionGetUserDuties() {
        $user_id = trim(\Yii::$app->request->get('user_id'));
        $user_ids = array_map('trim', explode(",", rtrim($user_id, ',')));
        $users_cnt = count($user_ids);
        $response = new Response();
        $result = null;
        for($i = 0; $i < $users_cnt; $i++) {
            $result = OrgPostAction::getUserDuties($user_ids[$i]);
            for($y = 1; $y < $users_cnt; $y++) {
                if ($result != OrgPostAction::getUserDuties($user_ids[$y])) {
                    $result = null;
                    break;
                }
            }
            break;
        }
        $response->is_error = false;
        $response->data = $result;
        return $response;
    }

    /*public function actionGetUserDutiesPer() {
        $user_id = \Yii::$app->request->get('user_id');
        $response = new Response();
        $response->is_error = false;
        $response->data = OrgPostAction::getUserDutiesPer($user_id);
        return $response;
    }*/

    public function actionGetTaskDurations() {
        $response = new Response();
        $response->is_error = false;
        $response->data = TasksAction::getTaskDurationList();
        return $response;
    }

    public function actionGetTaskRepeatTypes() {
        $response = new Response();
        $response->is_error = false;
        $response->data = TasksAction::getTaskRepeatTypesList();
        return $response;
    }

    public function actionGetTaskCalendarTypes() {
        $response = new Response();
        $response->is_error = false;
        $response->data = TasksAction::getTaskCalendarTypesList();
        return $response;
    }

    public function actionExecuteTaskAsUser() {
        $data = \Yii::$app->request->post();
        $response = new Response();

        $fields = ['task_id', 'comment', 'file_name', 'file'];
        if ($fields != array_keys($data) || $data['task_id'] == '') {
            $response->error_message = 'Invalid json data';
            return $response;
        }

        $task_id = $data['task_id'];
        $comment = ($data['comment'] != '') ? $data['comment'] : null;
        $file_name_orig = $data['file_name'];
        $file = ($data['file'] != '') ? $data['file'] : null;
        $file_name = null;

        if ($file) {
            $dir = \Yii::getAlias('@jobsstaff/web/uploads/tasks');
            $fname = pathinfo($file_name_orig, PATHINFO_FILENAME);
            $fext = pathinfo($file_name_orig, PATHINFO_EXTENSION);
            $file_name = $fname.'_'.$task_id.'.'.$fext;
            if (file_put_contents($dir.'/'.$file_name, base64_decode($file)) === false) {
                $response->error_message = 'File upload error';
                return $response;
            }
        }

        $result = TasksAction::setDutyTaskExecuted($task_id, $comment, $file_name);
        switch($result) {
            case 0:
                $response->is_error = false;
                break;
            case -1:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Permission denied');
                break;
            default:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Operation failed');
        }
        return $response;

        /*$task_id = \Yii::$app->request->post('task_id');
        $comment = \Yii::$app->request->post('comment');
        $file = \Yii::$app->request->post('file');
        $file_name = null;
        $response = new Response();

        if (!$task_id) {
            $response->error_message = "Missing parameter: 'task_id'";
            return $response;
        }

        if ($file) {
            $dir = \Yii::getAlias('@jobsstaff/web/uploads/tasks');
            $file_name = 'file_from_user_'.$task_id.'.jpg';
            $base_64 = explode(',', $file);
            if (file_put_contents($dir.'/'.$file_name, base64_decode($base_64[1])) === false) {
                $response->error_message = 'faili ar aitvirta';
                return $response;
            }
        }

        $result = TasksAction::setDutyTaskExecuted($task_id, $comment, $file_name);
        switch($result) {
            case 0:
                $response->is_error = false;
                break;
            case -1:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Permission denied');
                break;
            default:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Operation failed');
        }
        return $response;*/
    }

    public function actionExecuteTaskAsHead() {
        $data = \Yii::$app->request->post();
        $response = new Response();

        $fields = ['task_id', 'comment', 'checked', 'mark', 'file_name', 'file'];
        if ($fields != array_keys($data) || $data['task_id'] == '') {
            $response->error_message = 'Invalid json data';
            return $response;
        }

        $task_id = $data['task_id'];
        $comment = ($data['comment'] != '') ? $data['comment'] : null;
        $file_name_orig = $data['file_name'];
        $file = ($data['file'] != '') ? $data['file'] : null;
        $checked = ($data['checked'] == 1) ? true : false;
        $mark = ($data['mark'] > 0) ? $data['mark'] : null;
        $file_name = null;

        if ($file) {
            $dir = \Yii::getAlias('@jobsstaff/web/uploads/tasks');
            $fname = pathinfo($file_name_orig, PATHINFO_FILENAME);
            $fext = pathinfo($file_name_orig, PATHINFO_EXTENSION);
            $file_name = $fname.'_'.$task_id.'.'.$fext;
            if (file_put_contents($dir.'/'.$file_name, base64_decode($file)) === false) {
                $response->error_message = 'File upload error';
                return $response;
            }
        }

        $result = TasksAction::setDutyTaskChecked($task_id, $mark, $comment, $file_name, $checked);
        switch($result) {
            case 0:
                $response->is_error = false;
                break;
            case -1:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Permission denied');
                break;
            default:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Operation failed');
        }
        return $response;

        /*$task_id = \Yii::$app->request->post('task_id');
        $comment = \Yii::$app->request->post('comment');
        $file = \Yii::$app->request->post('file');
        $checked = (\Yii::$app->request->post('checked') == 1) ? true : false;
        $mark = (\Yii::$app->request->post('mark') > 0) ? \Yii::$app->request->post('mark') : null;
        $file_name = null;
        $response = new Response();

        if (!$task_id) {
            $response->error_message = "Missing parameter: 'task_id'";
            return $response;
        }

        if ($file) {
            $dir = \Yii::getAlias('@jobsstaff/web/uploads/tasks');
            $file_name = 'file_from_head_'.$task_id.'.jpg';
            $base_64 = explode(',', $file);
            if (file_put_contents($dir.'/'.$file_name, base64_decode($base_64[1])) === false) {
                $response->error_message = 'faili ar aitvirta';
                return $response;
            }
        }

        $result = TasksAction::setDutyTaskChecked($task_id, $mark, $comment, $file_name, $checked);
        switch($result) {
            case 0:
                $response->is_error = false;
                break;
            case -1:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Permission denied');
                break;
            default:
                $response->is_error = true;
                $response->error_message = \Yii::t('Notifications', 'Operation failed');
        }
        return $response;*/
    }

    public function actionGetTaskUsers() {
        $task_header_id = \Yii::$app->request->get('task_header_id');

        $response = new Response();
        $response->is_error = false;
        $response->data = TasksAction::getTaskUsers($task_header_id);
        return $response;
    }

    public function actionRemoveTaskUsers() {
        $task_header_id = trim(\Yii::$app->request->post('task_header_id'));
        $users = trim(\Yii::$app->request->post('users'));
        $response = new Response();

        if (!$task_header_id) {
            $response->error_message = "Missing parameter: 'task_header_id'";
            return $response;
        }

        if (!$users) {
            $response->error_message = "Missing parameter: 'users'";
            return $response;
        }

        $user_ids = array_map(function($value) {
            return trim($value);
        }, explode(',', trim($users, ',')));

        $result = TasksAction::removeDutyTaskUsers($task_header_id, $user_ids);
        switch($result) {
            case 0:
                $response->is_error = false;
                break;
            case -1:
                $response->error_message = \Yii::t('Notifications', 'Permission denied');
                break;
            default:
                $response->error_message = \Yii::t('Notifications', 'Operation failed');
        }
        return $response;
    }

    public function actionChangeTaskName() {
        $task_header_id = trim(\Yii::$app->request->post('task_header_id'));
        $task_name = trim(\Yii::$app->request->post('task_name'));
        $response = new Response();

        if (!$task_header_id) {
            $response->error_message = "Missing parameter: 'task_header_id'";
            return $response;
        }

        if (!$task_name) {
            $response->error_message = "Missing parameter: 'task_name'";
            return $response;
        }

        $task_header = TasksHeader::findOne(['id' => $task_header_id]);
        if (!$task_header) {
            $response->error_message = "Task not found";
            return $response;
        }

        if ($task_header->created_by != \Yii::$app->user->getId()) {
            $response->error_message = \Yii::t('Notifications', 'Permission denied');
            return $response;
        }

        $task_header->task_name = $task_name;
        $result = $task_header->save();

        $response->is_error = !$result;
        $response->error_message = (!$result) ? \Yii::t('Notifications', 'Operation failed') : '';
        return $response;
    }

}