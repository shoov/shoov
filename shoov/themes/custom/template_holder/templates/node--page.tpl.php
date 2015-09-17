<div class="form-results">
  <div class="heading">
    <?php print $items['back_link']; ?>
  </div>
</div>

<div class="box box-content">
  <?php if (!empty($title)): ?>
    <div class="box-label"><?php print $title; ?></div>
  <?php endif; ?>
  <div class="content-image">
    <?php print render($content['field_image']); ?>
  </div>

  <div class="box-inside">
    <div class="description text">
      <div class="heading">
        <?php print $body_summary; ?>
      </div>

      <div class="content">
        <?php print render($content); ?>
      </div>
    </div>
  </div>
</div>
