<?php
  use app\core\form\Form;
  
  $this->title = 'Register';
?>
<h1>Create an account</h1>
<?php $form = Form::begin('', 'post') ?>

  <?php echo $form->field($model, 'username') ?>
  <?php echo $form->field($model, 'email') ?>
  <?php echo $form->field($model, 'password')->passwordField() ?>
  <?php echo $form->field($model, 'passwordConfirm')->passwordField() ?>

  <button type="submit" class="btn btn-primary">Submit</button>
  
<?php Form::end() ?>










