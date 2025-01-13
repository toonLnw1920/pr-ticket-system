<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assignments".
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $assigned_to
 * @property int $assigned_by
 * @property string|null $assigned_at
 * @property string|null $status
 *
 * @property User $assignedBy
 * @property User $assignedTo
 * @property Ticket $ticket
 */
class Assignments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assignments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'assigned_to', 'assigned_by'], 'required'],
            [['ticket_id', 'assigned_to', 'assigned_by'], 'integer'],
            [['assigned_at'], 'safe'],
            [['status'], 'string'],
            [['assigned_to'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['assigned_to' => 'id']],
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
            'assigned_to' => 'Assigned To',
            'assigned_by' => 'Assigned By',
            'assigned_at' => 'Assigned At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[AssignedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'assigned_by']);
    }

    /**
     * Gets query for [[AssignedTo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedTo()
    {
        return $this->hasOne(Users::class, ['id' => 'assigned_to']);
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
