<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string $category
 * @property string|null $status
 * @property string|null $priority
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Attachment[] $attachments
 * @property Calendar[] $calendars
 * @property Comment[] $comments
 * @property User $user
 */
class Tickets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public $uploadedFiles; // ฟิลด์ชั่วคราวสำหรับอัปโหลดไฟล์

    public function rules()
    {
        return [
            [['title', 'description', 'category'], 'required'],
            [['description'], 'string'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d'],
            [
                ['uploadedFiles'],
                'file',
                'skipOnEmpty' => true, // อนุญาตให้ไม่อัปโหลดไฟล์ได้
                'extensions' => 'jpg, jpeg, png, pdf, doc, docx',
                'maxFiles' => 5, // จำนวนไฟล์สูงสุดที่อัปโหลดได้
                'maxSize' => 1024 * 1024 * 10, // ขนาดไฟล์สูงสุด (10MB)
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'รหัสคำร้อง',
            'user_id' => 'User ID',
            'title' => 'ชื่อคำร้อง',
            'description' => 'คำอธิบาย',
            'category' => 'หมวดหมู่',
            'status' => 'สถานะ',
            'priority' => 'Priority',
            'created_at' => 'วัน/เดือน/ปี',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
            if (!Yii::$app->user->isGuest) {
                $this->user_id = Yii::$app->user->id; // ดึง user_id ปัจจุบัน
            } else {
                throw new \yii\web\ForbiddenHttpException('User must be logged in to create a ticket.');
            }
            $this->created_at = date('Y-m-d H:i:s'); // วันที่สร้าง
        }
        $this->updated_at = date('Y-m-d H:i:s'); // วันที่แก้ไข
        return parent::beforeSave($insert);
    }

    public function getCategoryLabel()
    {
        $categories = [
            'news' => 'ข่าว',
            'design' => 'ออกแบบ',
            'photo' => 'ถ่ายภาพ',
            'media' => 'ทำสื่อ',
        ];

        return $categories[$this->category] ?? $this->category; // แปลงค่า หรือแสดงค่าดั้งเดิมหากไม่มีการกำหนด
    }

    /**
     * Gets query for [[Attachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachments::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[Calendars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(Calendar::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignments()
    {
        return $this->hasMany(Assignments::class, ['ticket_id' => 'id']);
    }
}
