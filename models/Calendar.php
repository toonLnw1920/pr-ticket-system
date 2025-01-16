<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "calendar".
 *
 * @property int $id
 * @property string $event_name
 * @property string $event_date
 * @property int $ticket_id
 * @property string|null $created_at
 *
 * @property Ticket $ticket
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_name', 'event_date', 'ticket_id'], 'required'],
            [['event_date'], 'date', 'format' => 'php:Y-m-d'],
            [['ticket_id'], 'integer'],
            [['event_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_name' => 'ชื่อกิจกรรม',
            'event_date' => 'วันที่',
            'ticket_id' => 'รหัสคำขอ',
            'created_at' => 'วันที่สร้าง',
        ];
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
