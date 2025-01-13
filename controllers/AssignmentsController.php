<?php

namespace app\controllers;

use Yii;
use app\models\Assignments;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Tickets;
use app\models\Users;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * AssignmentsController implements the CRUD actions for Assignments model.
 */
class AssignmentsController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete', 'assign'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('❌ คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
                },
            ],
        ];
    }
    /**
     * Lists all Assignments models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Assignments::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Assignments model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Assignments::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('ไม่พบการมอบหมายนี้');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Assignments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($ticket_id)
    {
        $model = new Assignments();
        $ticket = Tickets::findOne($ticket_id);

        if (!$ticket) {
            throw new NotFoundHttpException('ไม่พบคำร้องที่ต้องการ');
        }

        // ดึงรายชื่อผู้ใช้ที่มี role เป็น 'user'
        $users = Users::find()->where(['role' => 'user'])->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->ticket_id = $ticket_id;
            $model->assigned_by = Yii::$app->user->id;
            $model->assigned_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'มอบหมายคำร้องสำเร็จ');
                return $this->redirect(['tickets/view', 'id' => $ticket_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users, // ตรวจสอบว่ามีการส่งตัวแปรนี้
        ]);
    }

    /**
     * Updates an existing Assignments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Assignments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Assignments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Assignments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Assignments::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
