<?php

$plugin = array(
  'label' => t('Notification toggle'),
  'description' => t('Toggle user notifications for a certain group.'),
  'name' => 'notification',
  'resource' => 'notification',
  'class' => 'RestfulNotification',
  'authentication_types' => TRUE,
);
