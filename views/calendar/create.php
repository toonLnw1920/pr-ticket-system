<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="calendar-create container-fluid">
   <!-- Header Section -->
   <div class="row mb-4">
       <div class="col-12">
           <div class="header-container p-4 bg-gradient-primary text-white rounded-3 shadow-sm">
               <div class="d-flex justify-content-between align-items-center flex-wrap">
                   <div>
                       <h1 class="h3 mb-2">เพิ่มกิจกรรม</h1>
                       <p class="mb-0 opacity-75">เพิ่มกิจกรรมใหม่ลงในปฏิทิน</p>
                   </div>
                   <?= Html::a(
                       '<i class="bi bi-arrow-left-circle-fill me-2"></i>กลับ',
                       ['index'],
                       ['class' => 'btn btn-light btn-lg shadow-sm']
                   ) ?>
               </div>
           </div>
       </div>
   </div>

   <!-- Form Card -->
   <div class="card shadow-lg">
       <div class="card-header bg-white py-4">
           <div class="d-flex align-items-center">
               <span class="form-icon-bg rounded-circle d-flex align-items-center justify-content-center me-3">
                   <i class="bi bi-calendar-plus text-primary"></i>
               </span>
               <h5 class="mb-0">กรอกข้อมูลกิจกรรม</h5>
           </div>
       </div>
       <div class="card-body p-4">
           <?php $form = ActiveForm::begin(['options' => ['class' => 'form-wrapper']]); ?>

           <div class="row">
               <div class="col-md-12 mb-4">
                   <?= $form->field($model, 'event_name')->textInput([
                       'class' => 'form-control form-control-lg',
                       'placeholder' => 'ชื่อกิจกรรม'
                   ])->label(false) ?>
               </div>

               <div class="col-md-6 mb-4">
                   <?= $form->field($model, 'event_date')->input('date', [
                       'class' => 'form-control form-control-lg',
                   ])->label(false) ?>
               </div>

               <div class="col-md-6 mb-4">
                   <?= $form->field($model, 'ticket_id')->dropDownList(
                       ArrayHelper::map($tickets, 'id', 'title'),
                       [
                           'prompt' => '-- เลือกคำขอ --',
                           'class' => 'form-control form-control-lg'
                       ]
                   )->label(false) ?>
               </div>
           </div>

           <div class="form-actions text-center mt-4">
               <?= Html::submitButton(
                   '<i class="bi bi-check-circle-fill me-2"></i>บันทึกข้อมูล',
                   ['class' => 'btn btn-primary btn-lg px-5']
               ) ?>
           </div>

           <?php ActiveForm::end(); ?>
       </div>
   </div>
</div>

<style>
   /* Gradient and Colors */
   .bg-gradient-primary {
       background: linear-gradient(135deg, #a73b24 0%, #923224 100%);
   }

   /* Form Icon Background */
   .form-icon-bg {
       width: 48px;
       height: 48px;
       background-color: #fff3f0;
   }
   
   .form-icon-bg i {
       font-size: 1.5rem;
   }

   /* Card Styling */
   .card {
       border: none;
       border-radius: 16px;
       transition: all 0.3s ease;
       overflow: hidden;
   }

   /* Form Controls */
   .form-control {
       border: 2px solid #e2e8f0;
       border-radius: 12px;
       padding: 12px 16px;
       transition: all 0.3s ease;
   }

   .form-control:focus {
       border-color: #a73b24;
       box-shadow: 0 0 0 0.2rem rgba(167, 59, 36, 0.15);
   }

   .form-control-lg {
       font-size: 1rem;
   }

   /* Button Styles */
   .btn-light {
       color: #a73b24;
       background: white;
       border: none;
       font-weight: 500;
       padding: 12px 26px;
       border-radius: 12px;
       transition: all 0.3s ease;
   }

   .btn-light:hover {
       background: #fff3f0;
       color: #923224;
       transform: translateY(-2px);
       box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
   }

   .btn-primary {
       background: #a73b24;
       border: none;
       font-weight: 500;
       transition: all 0.3s ease;
   }

   .btn-primary:hover {
       background: #923224;
       transform: translateY(-2px);
       box-shadow: 0 4px 12px rgba(167, 59, 36, 0.2);
   }

   /* Form Wrapper Animation */
   .form-wrapper {
       animation: fadeIn 0.5s ease-out;
   }

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

   /* Responsive Design */
   @media (max-width: 768px) {
       .header-container {
           padding: 1.5rem !important;
       }

       .btn-light, .btn-primary {
           padding: 8px 16px;
           font-size: 0.875rem;
       }

       .form-control-lg {
           font-size: 0.875rem;
           padding: 8px 12px;
       }

       .form-icon-bg {
           width: 40px;
           height: 40px;
       }

       .form-icon-bg i {
           font-size: 1.25rem;
       }
       
       .form-actions {
           text-align: center;
       }

       .btn-lg {
           width: 100%;
       }
   }

   /* Focus States */
   .form-control:focus {
       border-color: #a73b24;
       box-shadow: 0 0 0 0.2rem rgba(167, 59, 36, 0.15);
   }

   /* Placeholder Styling */
   .form-control::placeholder {
       color: #a0aec0;
       font-size: 0.875rem;
   }
</style>