<?php
namespace rest\modules\v1\models\task;

use api\actions\TasksAction;
use api\models\database\tasks\TasksCalendarType;
use api\models\database\tasks\TasksRepeatType;
use api\models\request\TaskParams;
use yii\base\Model;
use yii\base\Security;
use yii\web\UploadedFile;

class TaskModel extends Model {
    public $name;
    public $description;
    public $from;
    public $to;
    public $duration;
    public $repeat_type;
    public $priority_type;
    public $notify_emp;
    public $notify_head;
    public $users;
    public $duty_id;
    public $program;
    public $files = [];

    public function rules() {
        return [
            [['name', 'from', 'to', 'repeat_type', 'users', 'program'], 'required'],
            /*['to', 'required', 'when' => function($model) {
                return $model->repeat_type != TasksRepeatType::findOne(['repeat_type_key' => 'no repeat'])->id;
            }],*/
            [['description', 'duration', 'priority_type', 'notify_emp', 'notify_head', 'duty_id'], 'safe'],
            [['name', 'description', 'from', 'to', 'duration', 'repeat_type', 'priority_type', 'notify_emp', 'notify_head', 'users', 'duty_id', 'program', 'files'], 'trim'],
            ['name', 'string', 'max' => 50],
            [['from', 'notify_emp', 'notify_head'], 'number', 'min' => 1],
            [['to', 'duty_id'], 'number'],
            //['to', 'compare', 'compareAttribute' => 'from', 'operator' => '>', 'message' => 'End date shouldn\'t be earlier than start'],
            ['to', function($model, $attribute) {
                if ($this->to <= 0 && $this->repeat_type != TasksRepeatType::findOne(['repeat_type_key' => 'no repeat'])->id) {
                    $this->addError($model, 'Enter end date');
                    return false;
                }

                if ($this->to > 0 && $this->to <= $this->from) {
                    $this->addError($model, 'End date shouldn\'t be earlier than start');
                    return false;
                }
            }],
            ['repeat_type', 'exist', 'targetClass' => TasksRepeatType::className(), 'targetAttribute' => 'id'],
            ['priority_type', 'exist', 'targetClass' => TasksCalendarType::className(), 'targetAttribute' => 'id'],
            ['program', 'in', 'range' => [1, 2]]
        ];
    }

    /*public function attributeLabels() {
        return [
            'name' => \Yii::t('manager', 'Task name'),
            'from' => \Yii::t('manager', 'From'),
            'to' => \Yii::t('manager', 'To'),
            'repeat_type' => \Yii::t('main', 'Repeat'),
        ];
    }*/

    public function save() {
        $task = new TaskParams();

        $users_array = array_map(function($value) {
            return trim($value);
        }, explode(',', trim($this->users, ',')));

        foreach($this->files as $file) {
            $dir = \Yii::getAlias('@jobsstaff/web/uploads/tasks_headers');
            $file_name_orig = trim($file['file_name']);
            $extension = pathinfo($file_name_orig, PATHINFO_EXTENSION);
            $file_name = \Yii::$app->security->generateRandomString().'.'.$extension;
            $file = trim($file['file']);

            if (file_put_contents($dir.'/'.$file_name, base64_decode($file)) !== false) {
                $task->filenames[] = $file_name;
                $task->file_names_orig[] = $file_name_orig;
            }
        }

        $task->task_name = $this->name;
        $task->task_comment = $this->description;
        $task->task_start = $this->from;
        $task->task_end = ($this->to > 0) ? $this->to : null;
        $task->cycle_length = $this->duration;
        $task->repeat_type_id = $this->repeat_type;
        $task->calendar_type_id = $this->priority_type;
        $task->notify_emp_hour_before = $this->notify_emp ? floor($this->notify_emp / 60) : null;
        $task->notify_emp_minute_before = $this->notify_emp ? $this->notify_emp % 60 : null;
        $task->notify_head_hour_before = $this->notify_head ? floor($this->notify_head / 60) : null;
        $task->notify_head_minute_before = $this->notify_head ? $this->notify_head % 60 : null;
        $task->notify_employee = $this->notify_emp ? true : false;
        $task->notify_head = $this->notify_head ? true : false;

        return TasksAction::addNewTaskForDuty($users_array, ($this->duty_id > 0) ? $this->duty_id : null, $task, $this->program);
    }
}