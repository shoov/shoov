<div id="wrapper" class="clearfix">
  <?php if (isset($tabs)): ?>
    <div class="tabs">
      <?php print render($tabs); ?>
    </div>
  <?php endif; ?>

  <?php print $header; ?>

  <div id="messages">
    <?php print $messages; ?>
  </div>

  <main id="main" class="main-mobile" role="main">
    <div class="mobile-content">
      <?php print render($page['content']); ?>
    </div>
  </main>
</div>

<footer id="footer" class="mobile-footer clearfix">
  <div class="holder">
    <div class="column content pull-right">
      <strong class="title"><span class="icon-copyright">©</span> כל הזכויות שמורות לאתוסיה</strong>
    </div>
  </div>
</footer>
