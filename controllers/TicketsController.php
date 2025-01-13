<?php

namespace app\controllers;

use Yii;
use app\models\Tickets;
use app\models\TicketsSearch;
use app\models\Attachments;
use app\models\Assignments;
use app\models\Users;
use app\models\Notifications;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\filters\VerbFilter;
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
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'view', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->request->get('id');
                            $ticket = $this->findModel($id);
                            return Yii::$app->user->identity->role === 'admin' ||
                                $ticket->user_id === Yii::$app->user->id;
                        }
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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $attachmentModel = new Attachments();

        // ตรวจสอบสิทธิ์ (admin หรือเจ้าของคำร้อง)
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
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // ตรวจสอบสิทธิ์ (admin หรือเจ้าของคำร้อง)
        if (Yii::$app->user->identity->role !== 'admin' && $model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('คุณไม่มีสิทธิ์ลบคำร้องนี้');
        }

        // ลบไฟล์แนบทั้งหมดที่เกี่ยวข้อง
        $attachments = Attachments::findAll(['ticket_id' => $id]);
        foreach ($attachments as $attachment) {
            $filePath = Yii::getAlias('@webroot/') . $attachment->file_path;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $attachment->delete();
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'ลบคำร้องเรียบร้อยแล้ว');

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

    public function actionChangeStatus($id)
    {
        $model = $this->findModel($id);

        // ตรวจสอบสิทธิ์
        if (!Yii::$app->user->can('manageTickets')) {
            throw new ForbiddenHttpException('คุณไม่มีสิทธิ์เปลี่ยนสถานะคำร้องนี้');
        }

        if ($this->request->isPost) {
            $status = Yii::$app->request->post('status');
            $validStatuses = ['pending', 'approved', 'rejected', 'in_progress', 'completed'];

            if (in_array($status, $validStatuses)) {
                $model->status = $status;
                $model->updated_at = date('Y-m-d H:i:s');

                try {
                    if ($model->save()) {
                        // สร้างการแจ้งเตือน
                        $notification = new Notifications();
                        $notification->user_id = $model->user_id;
                        $notification->message = "คำร้องของคุณ \"{$model->title}\" มีการเปลี่ยนสถานะเป็น " . $model->getStatusLabel();

                        if ($notification->save()) {
                            Yii::$app->session->setFlash('success', 'เปลี่ยนสถานะคำร้องเรียบร้อยแล้ว');
                        } else {
                            Yii::error('Notification save error: ' . json_encode($notification->getErrors()));
                            // ยังให้ผ่านไปได้แม้การแจ้งเตือนจะไม่สำเร็จ
                            Yii::$app->session->setFlash('success', 'เปลี่ยนสถานะคำร้องเรียบร้อยแล้ว แต่ไม่สามารถสร้างการแจ้งเตือนได้');
                        }
                    } else {
                        Yii::error('Ticket status update error: ' . json_encode($model->getErrors()));
                        Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . implode(', ', $model->getFirstErrors()));
                    }
                } catch (\Exception $e) {
                    Yii::error('Exception while updating ticket status: ' . $e->getMessage());
                    Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาดในระบบ: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'สถานะที่เลือกไม่ถูกต้อง');
            }
            return $this->redirect(['view', 'id' => $id]);
        }

        throw new MethodNotAllowedHttpException('Invalid request method.');
    }

    protected function findModel($id)
    {
        if (($model = Tickets::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
