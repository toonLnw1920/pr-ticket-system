<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Calendar;
use app\models\Tickets;
use edofre\fullcalendar\models\Event;

class CalendarController extends Controller
{
    public function actionIndex()
    {
        $events = [];
        $calendar_events = Calendar::find()
            ->with('ticket')  // eager loading tickets data
            ->all();
        
        foreach ($calendar_events as $event) {
            // สร้าง event object สำหรับ FullCalendar
            $events[] = new Event([
                'id' => $event->id,
                'title' => $event->event_name . ' (' . $event->ticket->title . ')',
                'start' => $event->event_date,
                'allDay' => true,
                // เพิ่ม URL สำหรับคลิกดูรายละเอียด ticket
                'url' => Yii::$app->urlManager->createUrl(['tickets/view', 'id' => $event->ticket_id])
            ]);
        }

        return $this->render('index', [
            'events' => $events
        ]);
    }

    public function actionCreate()
    {
        $model = new Calendar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'เพิ่มกิจกรรมเรียบร้อยแล้ว');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'tickets' => Tickets::find()->all() // ส่งข้อมูล tickets ไปแสดงใน dropdown
        ]);
    }
}