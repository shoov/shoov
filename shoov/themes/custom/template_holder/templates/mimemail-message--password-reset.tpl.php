<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php if ($css): ?>
    <style type="text/css">
      <!--
      <?php print $css; ?>
      -->
    </style>
  <?php endif; ?>
</head>
<body dir="rtl" id="mimemail-body" <?php if ($module && $key): print 'class="'. $module .'-'. $key .'"'; endif; ?>>

<div class="background">
  <div class="logo-container">
    <img alt="ethosia" src="<?php print $images_path; ?>/logo.png" />
  </div>
  <div class="container">

    <div class="password-reset">
      <?php print $body; ?>
    </div>

    <div class="footer second">
      <div class="icons">
        <a href="https://www.youtube.com/channel/UCHER6NZGJd3ry1Hh7pQUoaw" title="youtube"><img src="<?php print $images_path; ?>/youtube.png" alt="youtube" /></a>
        <a href="https://www.linkedin.com/company/ethosia" title="linked-in"><img src="<?php print $images_path; ?>/linkedin.png" alt="linked-in" /></a>
        <a href="https://plus.google.com/%2Bethosia/posts" title="google+"><img src="<?php print $images_path; ?>/googleplus.png" alt="google+" /></a>
        <a href="https://twitter.com/ethosia" title="twitter"><img src="<?php print $images_path; ?>/twitter.png" alt="twitter" /></a>
        <a href="https://www.facebook.com/ethosia" title="facebook"><img src="<?php print $images_path; ?>/facebook.png" alt="facebook" /></a>
        <a href="http://www.ethosia.co.il/" title="אתר"><img src="<?php print $images_path; ?>/website.png" alt="אתר" /></a>
        <a href="tel:03-7678999" title="טלפון"><img src="<?php print $images_path; ?>/phone.png" alt="טלפון" /></a>
      </div>
      <div class="text-links">
        <a href="http://www.ethosia.co.il/">שלח קורות חיים</a>
        <div></div>
        <a href="http://www.ethosia.co.il/">www.ethosia.co.il</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
