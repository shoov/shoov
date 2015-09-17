<div id="wrapper">

  <div id="messages">
    <?php print $messages; ?>
  </div>

  <?php if (isset($tabs)): ?>
  <div class="tabs">
    <?php print render($tabs); ?>
  </div>
  <?php endif; ?>

  <div class="container">

    <div class="row">
      <header id="header">
        <div class="header-content">
          <strong class="logo"><a href="<?php print url('<front>'); ?>">Ethosia. Human Resources</a></strong>
        </div>
      </header>
    </div>

    <div class="row">
      <main id="main" role="main">
        <?php print render($page['content']); ?>
      </main>
    </div>

  </div>

</div>
