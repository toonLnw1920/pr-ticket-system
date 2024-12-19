<?php

namespace app\controllers;

use Yii;
use app\models\Tickets;
use app\models\TicketsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Attachments;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;


/**
 * TicketsController implements the CRUD actions for Tickets model.
 */
class TicketsController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'], // กำหนด action ที่ต้องการควบคุม
                'rules' => [
                    [
                        'actions' => ['login'], // อนุญาตให้เข้าถึง action login ได้เท่านั้น
                        'allow' => true,
                        'roles' => ['?'], // '?' หมายถึง Guest หรือผู้ที่ยังไม่ได้ Login
                    ],
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'], // อนุญาตเฉพาะ user ที่ login แล้ว
                        'allow' => true,
                        'roles' => ['@'], // '@' หมายถึง ผู้ที่ Login แล้ว
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Tickets models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TicketsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // ถ้าเป็น User ให้แสดงเฉพาะคำร้องของตัวเอง
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'user') {
            $dataProvider->query->andWhere(['user_id' => Yii::$app->user->id]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tickets model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    $model = new Tickets();

    if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
            // กำหนด user_id ให้เป็นผู้ใช้ที่ล็อกอิน
            $model->user_id = Yii::$app->user->id;

            // จัดการไฟล์ที่อัปโหลด
            $uploadedFiles = \yii\web\UploadedFile::getInstances($model, 'uploadedFiles');

            if ($model->save()) {
                // ถ้ามีไฟล์ที่อัปโหลด
                if ($uploadedFiles) {
                    // สร้างโฟลเดอร์ถ้ายังไม่มี
                    $uploadPath = Yii::getAlias('@webroot/uploads/');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    foreach ($uploadedFiles as $file) {
                        // สร้างชื่อไฟล์ใหม่
                        $newFileName = uniqid() . '_' . $file->baseName . '.' . $file->extension;
                        // กำหนด path เต็ม
                        $filePath = $uploadPath . $newFileName;
                        
                        // บันทึกไฟล์ลงโฟลเดอร์
                        if ($file->saveAs($filePath)) {
                            // บันทึกข้อมูลไฟล์ในตาราง attachments
                            $attachment = new Attachments();
                            $attachment->ticket_id = $model->id;
                            // เก็บ path สัมพันธ์สำหรับการแสดงผล
                            $attachment->file_path = 'uploads/' . $newFileName;
                            $attachment->file_type = $file->extension;
                            $attachment->uploaded_at = date('Y-m-d H:i:s');
                            $attachment->save();
                        } else {
                            Yii::$app->session->setFlash('error', 'ไม่สามารถอัปโหลดไฟล์ได้');
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    /**
     * Updates an existing Tickets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // ตรวจสอบสิทธิ์เฉพาะเจ้าของหรือ admin
        if (Yii::$app->user->identity->role !== 'admin' && $model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('คุณไม่มีสิทธิ์แก้ไขคำร้องนี้');
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tickets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // ตรวจสอบสิทธิ์เฉพาะ admin เท่านั้น
        if (Yii::$app->user->identity->role !== 'admin') {
            throw new ForbiddenHttpException('คุณไม่มีสิทธิ์ลบคำร้องนี้');
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tickets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tickets::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
