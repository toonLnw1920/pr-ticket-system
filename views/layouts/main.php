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
$this->registerLinkTag([
    'rel' => 'stylesheet',
    'href' => 'https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap'
]);
$this->registerLinkTag([
    'rel' => 'stylesheet',
    'href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'
]);
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
                        <?= Html::a('<i class="fas fa-tachometer-alt me-2"></i>Dashboard', ['/site/dashboard'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                        <?= Html::a('<i class="fas fa-ticket-alt me-2"></i>จัดการคำร้อง', ['/tickets/index'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                        <?= Html::a('<i class="far fa-calendar-alt me-2"></i>Calendar', ['/calendar/index'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                    <?php else: ?>
                        <!-- Sidebar สำหรับ User -->
                        <?= Html::a('<i class="fas fa-ticket-alt me-2"></i>คำร้องทั้งหมด', ['/tickets/index'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                        <?= Html::a('<i class="far fa-chart-bar me-2"></i>Reports', ['/site/reports'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- สำหรับผู้ที่ยังไม่ล็อกอิน -->
                    <?= Html::a('<i class="fas fa-sign-in-alt me-2"></i>Login', ['/site/login'], ['class' => 'list-group-item list-group-item-action bg-dark text-white']) ?>
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
                    </div>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            <?php if (!Yii::$app->user->isGuest): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user me-2"></i><?= Html::encode(Yii::$app->user->identity->name) ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <li><?= Html::a('<i class="fas fa-user-circle me-2"></i>Profile', ['/site/profile'], ['class' => 'dropdown-item']) ?></li>
                                        <li><?= Html::a('<i class="fas fa-sign-out-alt me-2"></i>Logout', ['/site/logout'], [
                                                'class' => 'dropdown-item',
                                                'data-method' => 'post'
                                            ]) ?></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <?= Html::a('<i class="fas fa-sign-in-alt me-2"></i>Login', ['/site/login'], ['class' => 'btn text-white']) ?>
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