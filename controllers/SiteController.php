<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Users;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Tickets;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['dashboard', 'logout'],
                        'allow' => true,
                        'roles' => ['@'], // '@' = Logged in user
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionDashboard()
    {
        // Query ข้อมูลคำร้อง
        $totalTickets = Tickets::find()->count();
        $pendingTickets = Tickets::find()->where(['status' => 'pending'])->count();
        $completedTickets = Tickets::find()->where(['status' => 'completed'])->count();
        $inProgressTickets = Tickets::find()->where(['status' => 'in_progress'])->count();

        // คำร้องล่าสุด 5 รายการ
        $latestTickets = Tickets::find()->orderBy(['created_at' => SORT_DESC])->limit(5)->all();

        return $this->render('dashboard', [
            'totalTickets' => $totalTickets,
            'pendingTickets' => $pendingTickets,
            'completedTickets' => $completedTickets,
            'inProgressTickets' => $inProgressTickets,
            'latestTickets' => $latestTickets,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome(); // หาก login อยู่แล้วให้กลับไปหน้าแรก
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // ตรวจสอบ role และ redirect ตามสิทธิ์
            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'user') {
                return $this->redirect(['tickets/index']); // สำหรับ User
            } elseif (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'admin') {
                return $this->redirect(['site/dashboard']); // สำหรับ Admin
            } else {
                return $this->goHome(); // สำหรับ role อื่นๆ
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['site/login']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
   
     /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
