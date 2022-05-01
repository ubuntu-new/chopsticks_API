<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 04/17/22
 * Time: 18:59
 */

namespace rest\modules\v1\controllers\webertela;


use api\models\database\webetrela\User;
use yii\rest\ActiveController;
use yii\rest\Controller;

class AppController extends Controller
{
    public $modelClass = User::class;





}