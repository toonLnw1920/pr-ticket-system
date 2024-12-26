<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // สร้าง role admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);

        // สร้างสิทธิ์ต่างๆ
        $assignTicket = $auth->createPermission('assignTicket');
        $assignTicket->description = 'Assign tickets to users';
        $auth->add($assignTicket);

        // ให้สิทธิ์กับ role admin
        $auth->addChild($admin, $assignTicket);

        // กำหนด user เป็น admin (ใส่ ID ของ user ที่ต้องการ)
        $auth->assign($admin, 1); // 1 คือ ID ของ user

        echo "RBAC initialization completed.\n";
    }
}