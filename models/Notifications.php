<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property int|null $is_read
 * @property string|null $created_at
 *
 * @property User $user
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'message'], 'required'],
            ['user_id', 'integer'],
            ['message', 'string'],
            ['is_read', 'boolean'],
            ['is_read', 'default', 'value' => 0],
            ['created_at', 'safe'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ผู้ใช้',
            'message' => 'ข้อความ',
            'is_read' => 'อ่านแล้ว',
            'created_at' => 'วันที่สร้าง',
        ];
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
}
