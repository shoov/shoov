<div class="box">
  <?php if ($job_category_label): ?>
    <div class="btn-high-tech"><?php print $job_category_label; ?></div>
  <?php endif; ?>

  <div class="box-overlay-container">
    <div class="img-holder">
      <?php print render($content['field_image']); ?>
    </div>
  </div>

  <div class="title-holder">
    <?php print $social_networks; ?>
    <div class="text-title">
      <h2 class="title"><?php print $title; ?></h2>
      <h3><?php print render($content['field_job_title']);?></h3>
    </div>
  </div>
  <div class="text-holder add-style">
    <?php print render($content['body']); ?>
  </div>
</div>
