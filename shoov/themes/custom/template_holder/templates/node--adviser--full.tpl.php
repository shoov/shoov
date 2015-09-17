<div class="form-results">
  <div class="heading">
    <?php print $items['back_link']; ?>
  </div>
</div>

<div class="box box-content">
  <div class="box-label"><?php print render($content['field_job_category']); ?></div>

  <div class="content-image">
    <?php print render($content['field_image']);?>
  </div>

  <?php print $social_networks; ?>

  <div class="box-inside">
    <div class="description text">
      <div class="heading">
        <div class="title"><?php print $title; ?> </div>
        <?php print render($content['field_job_title']); ?>
      </div>
      <?php print render($content['body']); ?>
    </div>
  </div>
</div>
