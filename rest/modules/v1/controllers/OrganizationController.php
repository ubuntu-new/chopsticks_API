<?php
namespace rest\modules\v1\controllers;

use api\actions\OrganizationAction;
use api\actions\OrgPostAction;
use api\actions\TasksAction;
use api\models\response\Result;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\base\Security;
use yii\web\UploadedFile;

class OrganizationController extends RestController {

    public function actionGetUsers() {
        $org_id = OrganizationAction::getMyOrganizationId();
        $program = \Yii::$app->request->get('program');

        $users = OrganizationAction::getSubordinateUsers($program);
        $permitted = OrganizationAction::unitUsersForPermittedUsers($org_id, null, $program, null, null);

        $response = new Response();
        $response->is_error = false;
        $response->data = array_merge($users, $permitted);
        return $response;
    }

}