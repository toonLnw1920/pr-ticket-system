<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="bg-dark">
            <div class="sidebar-heading">
                <?= Html::a('KKU P.R.T.S', [''], ['class' => 'text-white text-decoration-none']) ?>
            </div>
            <div class="sidebar-divider">
                <small>ระบบส่งคำร้องประชาสัมพันธ์</small>
            </div>

            <div class="list-group list-group-flush">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?php if (Yii::$app->user->identity->role === 'admin'): ?>
                        <!-- Sidebar สำหรับ Admin -->
                        <?= Html::a('Dashboard', ['/site/dashboard'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                        <?= Html::a('จัดการคำร้อง', ['/tickets/index'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                        <?= Html::a('Calendar', ['/calendar/index'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                    <?php else: ?>
                        <!-- Sidebar สำหรับ User -->
                        <?= Html::a('คำร้องทั้งหมด', ['/tickets/index'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                        <?= Html::a('Reports', ['/site/reports'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- สำหรับผู้ที่ยังไม่ล็อกอิน -->
                    <?= Html::a('Login', ['/site/login'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">

            <!-- Header -->
            <nav class="navbar navbar-expand-lg" style="background-color: #a73b24;">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <button class="btn" id="sidebarToggle">☰</button>
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <span class="d-lg-none ms-2 text-white user-mobile">
                                <?= Html::encode(Yii::$app->user->identity->name) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            <?php if (!Yii::$app->user->isGuest): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= Html::encode(Yii::$app->user->identity->name) ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <li><?= Html::a('Profile', ['/site/profile'], ['class' => 'dropdown-item']) ?></li>
                                        <li><?= Html::a('Logout', ['/site/logout'], [
                                                'class' => 'dropdown-item',
                                                'data-method' => 'post'
                                            ]) ?></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <?= Html::a('Login', ['/site/login'], ['class' => 'btn text-white']) ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-ellipsis-v text-white"></i>
                    </button>
                </div>
            </nav>

            <!-- Main Content -->
            <main id="main" class="container mt-4">
                <?php if (!empty($this->params['breadcrumbs'])): ?>
                    <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
                <?php endif; ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </main>
        </div>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const wrapper = document.getElementById('wrapper');

            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                wrapper.classList.toggle('toggled');
            });
        });
    </script>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>