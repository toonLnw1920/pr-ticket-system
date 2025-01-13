<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    /**
     * Initializes RBAC roles, permissions, and assignments.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // 🧹 ล้างข้อมูลเก่า (ถ้ามี)
        $auth->removeAll();

        echo "🔄 ล้างการตั้งค่า RBAC เก่าเรียบร้อยแล้ว\n";

        // 📝 สร้างสิทธิ์ (Permissions)
        $assignTicket = $auth->createPermission('assignTicket');
        $assignTicket->description = 'Assign tickets to users';
        $auth->add($assignTicket);

        $manageTickets = $auth->createPermission('manageTickets');
        $manageTickets->description = 'Manage all tickets';
        $auth->add($manageTickets);

        $viewTickets = $auth->createPermission('viewTickets');
        $viewTickets->description = 'View tickets';
        $auth->add($viewTickets);

        echo "✅ เพิ่ม Permissions สำเร็จ\n";

        // 👤 สร้าง Role (Roles)
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);

        $supervisor = $auth->createRole('supervisor');
        $supervisor->description = 'Supervisor';
        $auth->add($supervisor);

        $user = $auth->createRole('user');
        $user->description = 'Standard User';
        $auth->add($user);

        echo "✅ เพิ่ม Roles สำเร็จ\n";

        // 🔗 กำหนดสิทธิ์ให้ Roles
        $auth->addChild($admin, $assignTicket);
        $auth->addChild($admin, $manageTickets);
        $auth->addChild($admin, $viewTickets);

        $auth->addChild($supervisor, $viewTickets);

        $auth->addChild($user, $viewTickets);

        echo "✅ กำหนดสิทธิ์ให้ Roles สำเร็จ\n";

        // 🆔 กำหนด Role ให้ User ตาม ID
        $auth->assign($admin, 1); // ID 1 -> Admin
        $auth->assign($supervisor, 2); // ID 2 -> Supervisor
        $auth->assign($user, 3); // ID 3 -> User

        echo "🎯 RBAC initialization completed successfully.\n";
    }
}
