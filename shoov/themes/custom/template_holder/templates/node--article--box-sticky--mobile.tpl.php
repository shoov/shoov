<div class="box box-content">
  <?php if (!empty($content['field_article_category'])): ?>
    <div class="box-label"><?php print render($content['field_article_category']); ?></div>
  <?php endif; ?>
  <div class="content-image">
    <?php print render($content['field_image']); ?>
  </div>

  <div class="box-inside">
    <div class="description text">
      <div class="heading">
        <div class="title"><?php print $title; ?> </div>
        <?php print render($content['field_summary']); ?>
      </div>
      <?php print render($content['body']); ?>
    </div>
  </div>
</div>
