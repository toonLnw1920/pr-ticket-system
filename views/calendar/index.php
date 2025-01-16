<?php
use edofre\fullcalendar\Fullcalendar;
use yii\web\JsExpression;
use yii\helpers\Html;
?>

<div class="calendar-index container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-container p-4 bg-gradient-primary text-white rounded-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1 class="h3 mb-2">ปฏิทินงาน</h1>
                        <p class="mb-0 opacity-75">จัดการและติดตามกิจกรรมทั้งหมดในระบบ</p>
                    </div>
                    <?= Html::a(
                        '<i class="bi bi-plus-circle-fill me-2"></i>เพิ่มกิจกรรม',
                        ['create'],
                        ['class' => 'btn btn-light btn-lg shadow-sm']
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Card -->
    <div class="card shadow-lg">
        <div class="card-header bg-white py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="calendar-icon-bg rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="bi bi-calendar3 text-primary"></i>
                    </span>
                    <h5 class="mb-0">จัดการกำหนดการปฏิทินงาน</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <?= Fullcalendar::widget([
                'options' => [
                    'id' => 'calendar',
                    'language' => 'th',
                ],
                'clientOptions' => [
                    'initialView' => 'dayGridMonth',
                    'headerToolbar' => [
                        'left' => 'prev,next today',
                        'center' => 'title',
                        'right' => 'dayGridMonth,dayGridWeek,dayGridDay'
                    ],
                    'selectable' => true,
                    'eventLimit' => true,
                    'height' => 'auto',
                    'firstDay' => 0,
                    'locale' => 'th',
                    'buttonText' => [
                        'today' => 'วันนี้',
                        'month' => 'เดือน',
                        'week' => 'สัปดาห์',
                        'day' => 'วัน',
                    ]
                ],
                'events' => $events ?? [],
            ]); ?>
        </div>
    </div>
</div>

<style>
    /* Gradient and Colors */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #a73b24 0%, #923224 100%);
    }

    /* Icon Background */
    .calendar-icon-bg {
        width: 48px;
        height: 48px;
        background-color: #fff3f0;
    }
    
    .calendar-icon-bg i {
        font-size: 1.5rem;
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Calendar Container */
    .fc {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
    }

    /* Calendar Header */
    .fc-toolbar-title {
        font-size: 1.5em !important;
        font-weight: 600 !important;
        color: #2d3748 !important;
    }

    /* Calendar Buttons */
    .fc-button {
        padding: 8px 16px !important;
        font-weight: 500 !important;
        border-radius: 8px !important;
        border: none !important;
        transition: all 0.3s ease !important;
    }

    .fc-button:hover {
        background: #923224 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(167, 59, 36, 0.2) !important;
    }

    /* Calendar Grid */
    .fc-day {
        transition: all 0.2s ease !important;
    }

    .fc-day:hover {
        background-color: #fff3f0 !important;
    }

    .fc-day-today {
        background-color: #fff3f0 !important;
    }

    /* Calendar Events */
    .fc-event {
        border: none !important;
        border-radius: 8px !important;
        padding: 4px 8px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
    }

    .fc-event:hover {
        transform: translateY(-2px) scale(1.02) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        z-index: 2 !important;
    }

    /* Add Event Button */
    .btn-light {
        color: #a73b24;
        background: white;
        border: none;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background: #fff3f0;
        color: #923224;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-container {
            padding: 1.5rem !important;
        }

        .btn-light {
            padding: 8px 16px;
            margin-top: 1rem;
            width: 100%;
        }

        .fc-toolbar {
            flex-direction: column;
            gap: 1rem;
        }

        .fc-toolbar-title {
            font-size: 1.25em !important;
            text-align: center;
        }

        .fc-button {
            padding: 6px 12px !important;
            font-size: 0.875rem !important;
        }

        .calendar-icon-bg {
            width: 40px;
            height: 40px;
        }

        .calendar-icon-bg i {
            font-size: 1.25rem;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .fc-day-today {
            background-color: rgba(167, 59, 36, 0.1) !important;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .calendar-index {
        animation: fadeIn 0.5s ease-out;
    }
</style>