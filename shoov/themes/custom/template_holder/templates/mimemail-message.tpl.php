<?php
/**
 * @file
 * Theme implementation to format an HTML mail.
 *
 * Available variables:
 * - $recipient: The recipient of the message
 * - $subject: The message subject
 * - $body: The message body
 * - $css: Internal style sheets
 * - $module: The sending module
 * - $key: The message identifier
 *
 * @see template_preprocess_mimemail_message()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="he" dir="rtl">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width"/>

  <?php if($css): ?>
    <style type="text/css">
      <?php print $css; ?>
    </style>
  <?php endif; ?>
</head>
<body class="mime-mail">

<h1 class="subject">
  <?php print $subject; ?>
</h1>
<div class="content">
  <?php print $body; ?>
</div>

</body>
</html>
