<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('attendance');?>
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
<h2><?php echo h($name);?></h2>
<h2><?php echo h($date);?></h2>

<?php
  echo $this->Form->create();
  echo $this->Form->input('status', array(
    'label'    => '出欠席',
    'options'  => Configure::read('attendance_status'),
    'selected' => $attendance_status
  ));
  echo $this->Form->input('edited_login_time', array(
    'label'      => '出席時刻',
    'type'       => 'time',
    'timeFormat' => 24,
    'selected'   => $login_time
  ));
  echo $this->Form->submit(__('更新'), array(
    'class' => 'btn btn-info',
    'div' => false
  ));
  echo $this->Form->end();
?>
