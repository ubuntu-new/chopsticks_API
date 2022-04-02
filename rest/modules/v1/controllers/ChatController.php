<?php
namespace rest\modules\v1\controllers;

use api\actions\AddressBookAction;
use api\actions\InboxAction;
use api\actions\TasksAction;
use api\actions\UserAction;
use api\models\response\Result;
use Kerox\Push\Adapter\Fcm;
use Kerox\Push\Push;
use rest\controllers\RestController;
use yii\base\Security;
use yii\imagine\Image;

class ChatController extends RestController  {

    // get address bookis
    public function actionGetAddressBook() {

        return UserAction::test();
    }


    public function actionGetThemes() {
        return AddressBookAction::getThemes($_POST["ab_id"]);
    }

    public function actionGetAddressBookWithoutGroup() {
        return AddressBookAction::getAddressBookWithoutGroup($_POST["page"], -1, intval($_POST["items_per_page"]));
    }

    public function actionGetUsersInGroup() {
        return AddressBookAction::getUsersInGroup($_POST["ab_id"]);
    }

    public function actionGetAddressBookNoGroup() {
        return  AddressBookAction::getAddressBookNoGroup($_POST["ab_id"]);
    }

    public function actionCreateGroupInAddressBook() {

        $user_ids = $_POST["user_id"];

        array_push($user_ids, \Yii::$app->user->id);

        $address_book_id = AddressBookAction::addGroupInAddressBook($user_ids, -1,$_POST["group_name"]);
        $theme_id =  AddressBookAction::addNewTheme($_POST["theme_name"],$address_book_id);
        return  ["ab_id"=>$address_book_id, "theme_id"=>$theme_id];
    }

    public function actionAddUsersInGroup() {

        $user_ids = $_POST["user_ids"];


        $address_book_id = AddressBookAction::addUsersInGroup($user_ids, $_POST["ab_id"]);
        if ($address_book_id == 0)
            return   $_POST["ab_id"];
        else return -1;
    }



    public function actionGetFullConversation(){
        return  InboxAction::getFullConversation(intval($_POST["showfiles"]), intval($_POST["theme_id"]), -1, \Yii::$app->user->getId(), intval($_POST["page"]));
    }
    public function actionNewSmsFromAddressBookUser(){
        return AddressBookAction::newSmsFromAddressBookUserNew();
    }

    public function actionNewSmsForTheme(){
        return AddressBookAction::newSmsForThemesNew($_POST["theme_ids"]);
    }


    public function actionAddNewTheme() {
        if(\Yii::$app->request->post('theme_name') && \Yii::$app->request->post('ab_id'))
        return AddressBookAction::addNewTheme($_POST["theme_name"], $_POST["ab_id"]);
        else return Result::FAILURE;
    }

    public function actionLeaveTheme(){
        $theme_id = \Yii::$app->request->post('theme_id');
        /*-- ident = 0 remove only files, remove files and text--*/
        $ident = \Yii::$app->request->post('ident');

        return TasksAction::leaveTheme($theme_id, $ident);
    }
    public function actionSetSmsAsRead() {
        return InboxAction::setSmsAsReed($_POST["theme_id"], \Yii::$app->user->getId());
    }

    public function actionGetSizesSentByMe() {
        return TasksAction::getSizesSentByMe();
    }

    public function actionSendSms() {
        return  InboxAction::sendChatMessage($_POST["theme_id"],  $_POST["message"], $_POST["filename"],$_POST["filename_small"], $_POST["orig_filename"], $_POST["filesize"],  $_POST["send_mail"]);
    }

    public function actionUploadFile() {

        $dir = \Yii::getAlias('@jobsstaff/web/uploads/chat/').$_POST['theme_id'];
        $small_img = null;
        if (!is_dir($dir))
            mkdir($dir);

        $dir2 = $dir."/".\Yii::$app->user->getId();

        if (!is_dir($dir2))
            mkdir($dir2);

        $result = [];


            if (isset($_FILES['chatFile'])) {
                $security = new Security();
                $extension = pathinfo($_FILES['chatFile']['name'], PATHINFO_EXTENSION);
                $randomString = $security->generateRandomString();
                if ($extension == "jpg" || $extension == "JPG" || $extension == "gif" || $extension == "JPEG" ||$extension == "jpeg" ||  $extension == "png" ) {
                    $small_img = $randomString."_small".".".$extension;
                    Image::thumbnail($_FILES['chatFile']['tmp_name'], 50, 50)->save($dir2.'/'.$randomString."_small.".$extension);
                }

                if (move_uploaded_file($_FILES['chatFile']['tmp_name'], $dir2.'/'.$randomString.".".$extension))


                    $result = ["filename"=>$randomString.".".$extension, "smallImg" => $small_img, "filesize"=>$_FILES['chatFile']['size'], "orig_filename"=>$_FILES['chatFile']['name']];
            }

        return $result;
    }


    public function actionDeleteAddressBook() {
        return AddressBookAction::deteleAddressBook($_POST["ab_id"]);
    }

    public function actionAddRemoveFavorite(){
        return AddressBookAction::addRemoveFavorite($_POST["ab_id"]);
    }
}