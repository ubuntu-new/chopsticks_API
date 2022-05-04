<?php
namespace api\actions;
use api\models\database\Ingredients;
use api\models\database\OrderActions;
use api\models\database\Orders;
use api\models\database\Pcategory;
use api\models\database\Poses;
use api\models\database\PosesToCashier;
use api\models\database\Products;
use api\models\database\Status;
use api\models\database\TestOrders;
use api\models\database\User;
use api\models\response\ProductsResponse;
use api\models\response\ProductssiteResponse;
use api\models\response\Result;

use yii\base\Exception;
use yii\db\mssql\PDO;
use yii\helpers\Json;

class UserAction {
    public static function clockedInUsers($day = null) {
        $day = explode("to",$day);
        $sql = "select u.id, u.username from timesheet t 
                left join user u ON u.id = t.user_id 
                where t.created_at >= '".$day[0]." 00:00:00' and t.created_at <= '".$day[1]." 23:59:59' group by u.id";

        $row =  \Yii::$app->db->createCommand($sql)->queryAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($row as $r) {
            $result[]  =array_merge(["clockedindata"=>TimesheetAction::detailTimesheet(trim($day[0]),trim($day[1]),$r["id"])],$r);
        }
        return $result;
    }
    public static function getProductsOld() {
        $result = [];

        $sql = "SELECT {{p}}.* FROM {{products}} {{p}}
                    INNER JOIN {{product_category}} {{pc}} ON {{pc}}.[[id]] = {{p}}.[[category_id]] 
                WHERE {{p}}.[[status]] = 1 
                 ORDER BY {{pc}}.[[weight]], {{p}}.[[weight]] ASC ";



        $products = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);




        foreach ($products as $row) {
            $result[] = new ProductsResponse($row);
        }

        return $result;
    }
    public static function getProducts() {
        $result = [];

        $sql = "SELECT {{p}}.*,{{m}}.[[s]],{{m}}.[[m]],{{m}}.[[xl]] FROM {{products}} {{p}}
                    LEFT JOIN {{products_images}} {{m}} ON {{m}}.[[procts_id]] = [[p]].[[id]]
                    INNER JOIN {{product_category}} {{pc}} ON {{pc}}.[[id]] = {{p}}.[[category_id]] 
                WHERE {{p}}.[[status]] = 1 
                 ORDER BY {{pc}}.[[weight]], {{p}}.[[weight]] ASC ";



        $products = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);




        foreach ($products as $row) {
            $result[] = new ProductsResponse($row);
        }

        return $result;
    }

    public static function getProductssite() {
        $result = [];

        $sql = "SELECT {{p}}.*,{{m}}.[[s]],{{m}}.[[m]],{{m}}.[[xl]] FROM {{products}} {{p}}
                    LEFT JOIN {{products_images}} {{m}} ON {{m}}.[[procts_id]] = [[p]].[[id]]
                    INNER JOIN {{product_category}} {{pc}} ON {{pc}}.[[id]] = {{p}}.[[category_id]] 
                WHERE {{p}}.[[status]] = 1 AND  {{p}}.[[web]] = '1'
                 ORDER BY {{pc}}.[[weight]], {{p}}.[[weight]] ASC ";



        $productssite = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);




        foreach ($productssite as $row) {
            $result[] = new ProductssiteResponse($row);
        }

        return $result;
    }



    public static function getProductsCategory() {
        return Pcategory::find()->orderBy(['weight'=>SORT_ASC])->All();
    }
    public static function getIngredients($product_category) {
        if ($product_category == 0)
            $result = Ingredients::find()->where(['base'=>0])->orderBy(['weight' => SORT_ASC])->All();
        else $result = Ingredients::find()->where(['base'=>0])->andWhere(["product_category_id"=>$product_category])->andWhere(['status' => '1'])->orderBy(['weight' => SORT_ASC])->All();
        return $result;
    }

    public static function getReceiptByProductId($product_id=0) {

        $sql = "SELECT {{i}}.[[id]],{{i}}.[[name]],{{i}}.[[name_ge]],{{i}}.[[name_ru]] ,{{i}}.[[url]] , {{i}}.[[isPremium]] FROM {{receipt}} {{r}}
                    INNER JOIN {{ingredients}} {{i}} ON {{r}}.[[ingredients_id]] = {{i}}.[[id]]
                 WHERE {{r}}.[[product_id]] = :product_id ";



        $rows = \Yii::$app->db->createCommand($sql)
            ->bindValue(':product_id', $product_id)
            ->queryAll(\PDO::FETCH_ASSOC);


        return $rows;
    }

    public static function getOrderId($branch = null) {
        $date = date("Y-m-d");
        $orders = Orders::find()->where(["branch"=>$branch])->andWhere(["like","created_at",$date])->count();

        return "01".($orders+1);
    }
    public static function recieveOrderGlovo($data = null, $source = null) {




        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $order = new Orders();
            $order->order_data	 =  $data;
            $order->source = $source;
            $order->user_id = \Yii::$app->user->getId();


            $order->save();
            $transaction->commit();
            return Result::SUCCESS;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }
    public static function recieveOrder($data = null, $source = null) {
        $order_data = \Opis\Closure\unserialize($data);
        $order_id =  $order_data[0]["id"];
        switch($order_data[0]["status"]){
            case "pending":
                $status = 0;
                break;
            case "accepted":
                $status = 1;
                break;
            case "rejected":
                $status = 5;
                break;
            case "missed":
                $status =5;
                break;
            default:
                $status = 0;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $order = Orders::find()->where(["order_id"=>$order_id])->one();
            if ($order) {
                if ($status == 1)
                {
                    $start_date = strtotime($order_data[0]["accepted_at"]);
                    $end_date = strtotime($order_data[0]["fulfill_at"]);
                    $order->duration = ($end_date - $start_date)/60;
                }

                $order->status = $status;
                $order->order_data =  Json::encode($order_data);
            }
            else {
                $order = new Orders();
                $order->order_data	 =  Json::encode($order_data);
                $order->source = $source;
                $order->status = $status;
                $order->order_id = $order_data[0]["id"];
                $order->branch = $order_data[0]["restaurant_token"];
                $order->user_id = \Yii::$app->user->getId();
            }

            $order->save();
            $transaction->commit();
            return Result::SUCCESS;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function recieveOrderPos($data = null, $source = null) {
        $order_data = \Opis\Closure\unserialize($data);
        $order_id =  $order_data["orderId"];
        $status = 1;

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            if (isset($order_data["id"]) && $order_data["id"]==0) {

                $order = new Orders();
                $order->order_data	 =  Json::encode($order_data);
                $order->source = $source;
                $order->status = $status;
                $order->duration = isset($order_data["promise_time"])?$order_data["promise_time"]:15;
                $order->order_id = $order_id;
                $order->branch = "digomi";
                $order->user_id = \Yii::$app->user->getId();

            }


            else {
                $order = Orders::find()->where(["id"=>$order_data["id"]])->one();
                $old_order_data = \yii\helpers\Json::decode($order["order_data"]);

                if ($order_data["totalPrice"] >  $old_order_data["totalPrice"])
                    $action = "Refund";
                else
                    $action = "Add money";

                $order_action = new OrderActions();
                $order_action->action = $action;
                $order_action->order_id = $order_id;
                $order_action->user_id = \Yii::$app->user->getId();
                $order_action->data = $order->order_data;
                $order_action->save();

                $order_action = new OrderActions();
                $order_action->action = "Reopen";
                $order_action->order_id = $order_id;
                $order_action->user_id = \Yii::$app->user->getId();
                $order_action->data = $order->order_data;
                $order_action->save();

                $order->order_data	 =  Json::encode($order_data);
                $order->order_id = $order_id;
                $order->branch = "digomi";
                $order->user_id = \Yii::$app->user->getId();
                $order->save();

            }



            $order->save();
            $transaction->commit();
            return $order->id;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function waste($data = null, $source = null) {
        $order_data = \Opis\Closure\unserialize($data);
        $order_id =  $order_data["orderId"];
        $status = 1;

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            if (isset($order_data["id"]) && $order_data["id"]==0) {
                $order = new Orders();
                $order->order_data	 =  Json::encode($order_data);
                $order->source = $source;
                $order->status = $status;
                $order->duration = 15;
                $order->order_id = $order_id;
                $order->branch = "digomi";
                $order->user_id = \Yii::$app->user->getId();
            } else {
                $order = Orders::find()->where(["id"=>$order_id["id"]])->one();


                if ($order_data["totalPrice"] >  $order->totalPrice)
                    $action = "Refund";
                else
                    $action = "Add money";

                $order_action = new OrderActions();
                $order_action->action = $action;
                $order_action->order_id = $order_id;
                $order_action->user_id = \Yii::$app->user->getId();
                $order_action->data = $order->order_data;
                $order_action->save();

                $order_action = new OrderActions();
                $order_action->action = "Reopen";
                $order_action->order_id = $order_id;
                $order_action->user_id = \Yii::$app->user->getId();
                $order_action->data = $order->order_data;
                $order_action->save();

                $order->order_data	 =  Json::encode($order_data);
                $order->order_id = $order_id;
                $order->branch = "digomi";
                $order->user_id = \Yii::$app->user->getId();
                $order->save();

            }



            $order->save();
            $transaction->commit();
            return $order->id;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function getPlainPassword($user_id, $admin_pass) {
        if (\Yii::$app->user->identity->validatePassword($admin_pass)) {
            if ($user_id = User::find()->where(['id' => $user_id])->limit(1)->one())
                return ['success' => true, 'pass' => $user_id->plain_pass];
        }
        return ['success' => false, 'pass' => ''];
    }

    public static function getFullUserName($first_name, $last_name, $middle_name) {
        switch(\Yii::$app->language) {
            case 'en-US':
                return $first_name . ' ' . $last_name;
            default:
                return $last_name . ' ' . $first_name . ' ' . $middle_name;
        }
    }

    public static function getMyFullName() {
        if (\Yii::$app->user->isGuest)
            return 'Guest';
        $_user = \Yii::$app->user->identity;
        return self::getFullUserName($_user->first_name, $_user->last_name, $_user->middle_name);
    }

    public static function tryToFetchAccessToken($username, $password, $pin = false, $poses = null) {

        if ($pin) {
            $user = \common\models\User::findByPin($pin);
            if ($user) {
                $user->branch = $poses->branch_name;
                if ($user->save()) {
                    $postocashier = new PosesToCashier();
                    $postocashier->user_id = $user->id;
                    $postocashier->pos_id = $poses->id;
                    if($postocashier->save())
                        return $user;
                    else   return null;

                }
                return null;
            }
        }
        else
        {
            $user = \common\models\User::findByUsername($username);

            if ($user && $user->validatePassword($password))
                return $user;//->access_token;
        }
        return null;
    }

    public static function tryToFetchAccessTokenClocked($pin = false) {

        if ($pin) {
            $user = \common\models\User::findByPin($pin);
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    public static function saveFilterCountry($country_id) {
        if (\Yii::$app->user->isGuest)
            return Result::SUCCESS;

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $me = \Yii::$app->user->identity;
            $me->filter_country = $country_id;
            $me->save();
            $transaction->commit();
            return Result::SUCCESS;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;
    }

    /**
     * Gets list of users with given email
     *
     * @param string $email
     * @return SimpleUserResult[]
     */
    public static function getUsersByEmail($email) {
        $users_raw = User::find()->where(['email' => $email, 'status' => [Status::getActive(), Status::getPending()]])->all();
        $users = [];
        if ($users_raw) {
            foreach($users_raw as $user) {
                $users[] = new SimpleUserResult($user);
            }
        }
        return $users;
    }

    /**
     * Gets user by id as 1-element array
     *
     * @param integer $id
     * @return SimpleUserResult []
     */
    public static function getUsersById($id) {
        $user = User::findOne(['id' => $id, 'status' => [Status::getActive(), Status::getPending()]]);
        $users = [];
        if ($user) {
            $users[] = new SimpleUserResult($user);
        }
        return $users;
    }

    /**
     * Gets user by id and email as 1-element array

     * @param integer $id
     * @param string $email
     * @return SimpleUserResult []
     */
    public static function getUserByIdAndEmail($id, $email) {
        $user = User::findOne(['id' => $id, 'email' => $email, 'status' => [Status::getActive(), Status::getPending()]]);
        $users = [];
        if ($user) {
            $users[] = new SimpleUserResult($user);
        }
        return $users;
    }

    public static function getUsersByIdMailName($seach_text, $page = 1,  $items_per_page = 1) {
        $total = "SELECT COUNT(*) FROM ( ";
        /* if (isset($seach_text[1])) {
            $sql = "SELECT {{u}}.[[id]], {{u}}.[[first_name]], {{u}}.[[last_name]] ,{{uc}}.[[profile_photo]],{{uc}}.[[profile_photo_small]] FROM {{users}} {{u}}
                    INNER JOIN {{user_cv}} {{uc}} ON {{uc}}.[[user_id]] = {{u}}.[[id]] AND {{uc}}.[[is_default]] = 1
                    WHERE (({{u}}.{{first_name}} LIKE :first_name AND {{u}}.{{last_name}} LIKE :last_name ) OR
                          ({{u}}.{{last_name}} LIKE :first_name AND {{u}}.{{first_name}} LIKE :last_name )) AND
                          {{u}}.[[id]] NOT IN (SELECT {{abu2}}.[[user_id]] FROM {{address_book_users}} {{abu}}
                INNER JOIN {{address_book_users}} {{abu2}} ON {{abu}}.[[address_book_id]] = {{abu2}}.[[address_book_id]]
                INNER JOIN {{address_book}} {{ab}} ON {{abu2}}.[[address_book_id]] = {{ab}}.[[id]] AND {{ab}}.[[is_group]] = 0
                WHERE {{abu}}.[[user_id]] = :me AND {{abu}}.[[status]] IN (1,2)  AND {{abu2}}.[[user_id]] != :me AND {{abu2}}.[[status]] IN (1,2) GROUP BY {{abu2}}.[[user_id]])
                    ";*/
        /*   $users_raw = User::find()->andFilterWhere(["OR",
               ['AND',
                   ['like', 'first_name', '%'.$seach_text[0].'%', false],
                   ['like', 'last_name', '%'.$seach_text[1].'%', false]
               ],
               ['AND',
                   ['like', 'first_name', '%'.$seach_text[1].'%', false],
                   ['like', 'last_name', '%'.$seach_text[0].'%', false]
               ]])->all();*/
        //} else {
        $sql = "SELECT {{u}}.[[id]], {{u}}.[[first_name]], {{u}}.[[last_name]] ,{{uc}}.[[profile_photo]],{{uc}}.[[profile_photo_small]] FROM {{users}} {{u}}
                    INNER JOIN {{user_cv}} {{uc}} ON {{uc}}.[[user_id]] = {{u}}.[[id]] AND {{uc}}.[[is_default]] = 1
                    WHERE {{u}}.[[email]] = :mail AND
                    {{u}}.[[id]] NOT IN (SELECT {{abu2}}.[[user_id]] FROM {{address_book_users}} {{abu}}
                INNER JOIN {{address_book_users}} {{abu2}} ON {{abu}}.[[address_book_id]] = {{abu2}}.[[address_book_id]]
                INNER JOIN {{address_book}} {{ab}} ON {{abu2}}.[[address_book_id]] = {{ab}}.[[id]] AND {{ab}}.[[is_group]] = 0
                WHERE {{abu}}.[[user_id]] = :me AND {{abu}}.[[status]] in (1,2)  AND {{abu2}}.[[user_id]] != :me AND {{abu2}}.[[status]] in (1,2)   GROUP BY {{abu2}}.[[user_id]])";
        // }
        $sql_total = $total. $sql . " ) as {{s}}" ;

        $total_records_cmd = \Yii::$app->db->createCommand($sql_total);
        /*if (isset($seach_text[1])) {
            $total_records_cmd->bindValue(':first_name', '%'.$seach_text[0].'%')
                              ->bindValue(":last_name", '%'.$seach_text[1].'%')
                              ->bindValue(":me", \Yii::$app->user->id);
        } else {*/
        $total_records_cmd->bindValue(':mail', $seach_text)
            ->bindValue(":me", \Yii::$app->user->id);
        //}
        $total_records = $total_records_cmd->queryScalar();

        $total_records = 1 * $total_records;
        $total_pages = ceil($total_records / $items_per_page);
        $sql = $sql . "   LIMIT :start, :take";

        /* if (isset($seach_text[1])) {
            $rows= \Yii::$app->db->createCommand($sql)
                ->bindValue(':first_name', '%'.$seach_text[0].'%')
                ->bindValue(':last_name', '%'.$seach_text[1].'%')
                ->bindValue(":me", \Yii::$app->user->id)
                ->bindValue(":start", ($page - 1) * $items_per_page)
                ->bindValue(":take", $items_per_page)
                ->queryAll(\PDO::FETCH_ASSOC);

        } else {*/
        $rows = \Yii::$app->db->createCommand($sql)
            ->bindValue(':mail', $seach_text)
            ->bindValue(":me", \Yii::$app->user->id)
            ->bindValue(":start", ($page - 1) * $items_per_page)
            ->bindValue(":take", $items_per_page)
            ->queryAll(\PDO::FETCH_ASSOC);
        // };

        /*$users_raw = User::find()->andFilterWhere(['OR',
            ['id' => $seach_text[0]],
            ['like', 'first_name', '%'.$seach_text[0].'%', false],
            ['like', 'last_name', '%'.$seach_text[0].'%', false],
            ['email'=>$seach_text[0]]
        ])->all();*/

        $result = new UsersListForChat();
        $result->setPage($page);
        $result->setItemsPerPage($items_per_page);
        $result->setTotalPages($total_pages);
        $result->setTotalItems($total_records);
        $result->setUsers($rows);

        return $result;
    }

    /*public static function saveCurrentLanguage() {
        if (\Yii::$app->user->isGuest)
            return Result::SUCCESS;
        $me = User::find()->where(['id' => \Yii::$app->user->id])->limit(1)->one();
        if ($me) {
            $me->default_lang = \Yii::$app->language;
            if ($me->save()) {
                return Result::SUCCESS;
            }
        }
        return Result::FAILURE;
    }*/

    /*public static function sendUserEmail($user_id, $title, $message, $program = 'jobsstaff') {
        $user = User::find()->where(['id' => $user_id])->limit(1)->one();
        if (!$user || !$user->email)
            return Result::FAILURE;
        $is_jobsstaff = (strtolower($program) == 'jobsstaff');
        $images_dir = \Yii::getAlias("@common/mail/images/");
        $_program = $is_jobsstaff ? "JOBSSTAFF" : "WORKTASK";
        try {
            \Yii::$app->mailer->compose(['html' => 'generalTemplate-html', 'text' => 'generalTemplate-text'],
                [
                    'user' => $user,
                    'program' => $_program,
                    'bottom_img' => $images_dir . ($is_jobsstaff ? 'JsBottom.png' : 'WtBottom.png'),
                    'logo' => $images_dir . ($is_jobsstaff ? 'JS.png' : 'WT.png'),
                    'images_dir' => $images_dir,
                    'content' => $message
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => ($is_jobsstaff ? "jobsstaff " : "worktask ") . \Yii::t("EMails","Team")])
                ->setTo($user->email)
                ->setSubject($_program . ': ' . $title)
                ->send();
            return Result::SUCCESS;
        } catch (Exception $ex) {
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;
    }*/

}