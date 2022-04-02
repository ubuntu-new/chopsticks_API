<?php
namespace rest\modules\v1\models\task;

use api\actions\TasksAction;
use api\models\database\tasks\TasksRepeatType;
use api\models\request\TaskParams;
use api\models\response\Result;
use yii\base\Model;
use yii\web\UploadedFile;

class VoiceTaskModel extends Model {
    public $from;
    public $user;
    public $duty_id;
    public $file;
    public $program;

    public function rules() {
        return [
            [['from', 'user', 'duty_id', 'program'], 'required'],
            [['from', 'user', 'duty_id', 'program'], 'trim'],
            [['from', 'user', 'duty_id'], 'number', 'min' => 1],
            ['program', 'in', 'range' => [1, 2]]
            // ['file', 'safe'],
        ];
    }

    public function save() {
        $task = new TaskParams();

        $uploaded_file = UploadedFile::getInstanceByName('file');
        if ($uploaded_file) {
            $upload_dir = \Yii::getAlias('@jobsstaff/web/uploads/tasks_headers');
            $base_name = $uploaded_file->getBaseName();
            $extension = $uploaded_file->getExtension();
            $file_name = \Yii::$app->security->generateRandomString() . '.' . $extension;
            $file_name_orig = $base_name . '.' . $extension;
            if ($uploaded_file->saveAs($upload_dir . '/' . $file_name)) {
                $task->filenames[] = $file_name;
                $task->file_names_orig[] = $file_name_orig;
            }
        } else {
            return Result::FAILURE;
        }

        $task->task_name = $base_name;
        $task->task_start = $this->from;
        $task->repeat_type_id = TasksRepeatType::findOne(['repeat_type_key' => 'no repeat'])->id;

        return TasksAction::addNewTaskForDuty([$this->user], $this->duty_id, $task, $this->program);
    }
}