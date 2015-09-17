<li class="s-h-parent">
  <div class="holder col-sm-8 s-h-column">
    <div class="info">
      <h2><?php print $title; ?></h2>
      <h3><?php print render($content['field_job_title']);?></h3>
      <div class="text">
        <?php print render($content['body']); ?>
      </div>
      <?php print $social_networks; ?>
    </div>
  </div>
  <div class="frame image col-sm-4 s-h-column">
    <?php if (!empty($content['field_job_category'])): ?>
      <div class="btn-high-tech"><?php print render($content['field_job_category']);?></div>
    <?php endif; ?>
    <?php print render($content['field_image']); ?>
  </div>
</li>
