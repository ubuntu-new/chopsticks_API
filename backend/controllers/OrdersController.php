<?php

namespace backend\controllers;

use api\models\database\OrderStatus;
use api\models\database\webetrela\User;
use Yii;
use api\models\database\webetrela\Orders;
use api\models\database\webetrela\OrdersSearch;
use yii\web\Controller;
use api\actions\SmsActions;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','create','update','view'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPrint($id)
    {
        return $this->render('print', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionArchive()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('archive', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $driver_id = $model->driver_name;
            $status_id = $model->status;
            $customer = json_decode($model->customer);

            $status = OrderStatus::find()->where(['id'=>$status_id])->all();
            $driver = User::find()->where(['id'=>$driver_id])->all();
//                if($status[0]->id === 1){
//                    $messageText = "შემოსულია ახალი შეკვეთა";
//                    $mobile = "577230988";
//                    SmsActions::sendSmsShop($mobile, $messageText);  //  FOR Shop!!!!!
//                }
                if($status[0]->id === 2){
                    $messageTextCustomer = $status[0]->text;
                    SmsActions::sendSmsCustomer($customer->phone, $messageTextCustomer);  //  FOR CUSTOMER!!!!!
                }
                if($status[0]->id === 6){
                    $messageTextCustomer = $status[0]->text." Driver Phone Number# ".$driver[0]->phone;
                    $messageText ="Customer: ".$customer->fullName."Address: ".$customer->address." Direction- ".$customer->mapURL;
                    SmsActions::sendSmsCustomer($customer->phone, $messageTextCustomer);  //  FOR CUSTOMER!!!!!
                    SmsActions::sendSms($driver[0]->phone, $messageText);
                }
                if($status[0]->id === 5){
                    $messageTextCustomer = $status[0]->text." Driver Contact Number #: ".$driver[0]->phone;
                    SmsActions::sendSmsCustomer($customer->phone, $messageTextCustomer);
                }
                if($status[0]->id === 11){
                    SmsActions::sendSmsCustomer($customer->phone, $messageTextCustomer);  //  FOR CUSTOMER!!!!!
                }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
