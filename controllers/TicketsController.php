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
use app\models\Assignments;
use app\models\Users;


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
        $model = $this->findModel($id);

        // ดึงข้อมูลไฟล์แนบ
        $attachments = Attachments::find()->where(['ticket_id' => $id])->all();

        // เพิ่มการดึงข้อมูล users
        $users = Users::find()->where(['role' => 'user'])->all();

        return $this->render('view', [
            'model' => $model,
            'attachments' => $attachments,
            'users' => $users, // ส่งตัวแปร users ไปให้ view
        ]);
    }

    public function actionDownload($path)
    {
        $file = Yii::getAlias('@webroot') . '/' . $path;

        if (file_exists($file)) {
            return Yii::$app->response->sendFile($file);
        } else {
            throw new \yii\web\NotFoundHttpException('ไฟล์นี้ไม่มีอยู่ในระบบ.');
        }
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
                            // ใช้ชื่อไฟล์เดิม
                            $fileName = $file->baseName . '.' . $file->extension;

                            // ตรวจสอบว่าไฟล์ซ้ำหรือไม่
                            $counter = 1;
                            while (file_exists($uploadPath . $fileName)) {
                                $fileName = $file->baseName . '_' . $counter . '.' . $file->extension;
                                $counter++;
                            }

                            // กำหนด path เต็ม
                            $filePath = $uploadPath . $fileName;

                            // บันทึกไฟล์ลงโฟลเดอร์
                            if ($file->saveAs($filePath)) {
                                // บันทึกข้อมูลไฟล์ในตาราง attachments
                                $attachment = new Attachments();
                                $attachment->ticket_id = $model->id;
                                // เก็บ path สัมพันธ์สำหรับการแสดงผล
                                $attachment->file_path = 'uploads/' . $fileName;
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
        $attachmentModel = new Attachments();

        if (Yii::$app->user->identity->role !== 'admin' && $model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('คุณไม่มีสิทธิ์แก้ไขคำร้องนี้');
        }

        if ($model->load($this->request->post()) && $model->save()) {
            $uploadedFiles = \yii\web\UploadedFile::getInstances($model, 'uploadedFiles');

            if ($uploadedFiles) {
                $uploadPath = Yii::getAlias('@webroot/uploads/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                foreach ($uploadedFiles as $file) {
                    $fileName = $file->baseName . '.' . $file->extension;

                    // ตรวจสอบชื่อไฟล์ซ้ำ
                    $counter = 1;
                    while (file_exists($uploadPath . $fileName)) {
                        $fileName = $file->baseName . '_' . $counter . '.' . $file->extension;
                        $counter++;
                    }

                    $filePath = $uploadPath . $fileName;

                    if ($file->saveAs($filePath)) {
                        $attachment = new Attachments();
                        $attachment->ticket_id = $model->id;
                        $attachment->file_path = 'uploads/' . $fileName;
                        $attachment->file_type = $file->extension;
                        $attachment->uploaded_at = date('Y-m-d H:i:s');
                        if (!$attachment->save()) {
                            Yii::error('Error saving attachment: ' . json_encode($attachment->errors));
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'ไม่สามารถอัปโหลดไฟล์ได้');
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'แก้ไขคำร้องสำเร็จ!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'attachmentModel' => $attachmentModel,
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

    public function actionAssign($id)
    {
        $model = new Assignments();
        $ticket = $this->findModel($id);

        if (!$ticket) {
            throw new NotFoundHttpException('ไม่พบคำร้องที่ต้องการ');
        }

        if (!Yii::$app->user->can('assignTicket')) {
            throw new \yii\web\ForbiddenHttpException('❌ คุณไม่มีสิทธิ์มอบหมายคำร้องนี้');
        }

        if (Yii::$app->user->identity->role !== 'admin') {
            throw new ForbiddenHttpException('คุณไม่มีสิทธิ์มอบหมายคำร้องนี้');
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->ticket_id = $id;
            $model->assigned_by = Yii::$app->user->id;
            $model->assigned_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'มอบหมายคำร้องสำเร็จ');
                return $this->redirect(['view', 'id' => $id]);
            } else {
                Yii::$app->session->setFlash('error', 'ไม่สามารถมอบหมายคำร้องได้');
            }
        }

        $users = Users::find()->where(['role' => 'user'])->all();

        return $this->render('assign', [
            'model' => $model,
            'ticket' => $ticket,
            'users' => $users,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Tickets::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
