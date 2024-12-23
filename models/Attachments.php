<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachments".
 *
 * @property int $id
 * @property int|null $ticket_id
 * @property int|null $comment_id
 * @property string $file_path
 * @property string|null $file_type
 * @property string|null $uploaded_at
 *
 * @property Comment $comment
 * @property Ticket $ticket
 */
class Attachments extends \yii\db\ActiveRecord
{
    public $uploadedFiles; // เพิ่ม property สำหรับเก็บไฟล์ที่อัปโหลด

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'comment_id'], 'integer'],
            [['file_path'], 'required'],
            [['file_type'], 'string'],
            [['uploaded_at'], 'safe'],
            [['file_path'], 'string', 'max' => 255],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tickets::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comments::class, 'targetAttribute' => ['comment_id' => 'id']],
            [['uploadedFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, png, pdf', 'maxFiles' => 5],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'Ticket ID',
            'comment_id' => 'Comment ID',
            'file_path' => 'File Path',
            'file_type' => 'File Type',
            'uploaded_at' => 'Uploaded At',
        ];
    }

    /**
     * Gets query for [[Comment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comments::class, ['id' => 'comment_id']);
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Tickets::class, ['id' => 'ticket_id']);
    }
}
