<div class="form-results">
  <fieldset>
    <div class="heading">
      <?php print $items['back_link']; ?>
    </div>
  </fieldset>
</div>
<div class="box box-content">
  <?php if (!empty($article['article_manager'])): ?>
    <div class="box-label"><?php print $article['article_manager']; ?></div>
  <?php endif; ?>
  <div class="content-image">
    <?php print render($content['field_full_image']); ?>
  </div>

  <?php print $social_networks; ?>

  <div class="box-inside">
    <div class="description text">
      <div class="heading">
        <h1 class="title"><?php print $title; ?> </h1>
        <div class="introduction"><?php print render($content['field_summary']); ?></div>
        <div class="date-author">
          <?php print $date_published; ?>
          <?php if (!empty($content['field_author'])): ?>
          | מאת: <?php print render($content['field_author']); ?>
          <?php endif; ?>
        </div>

      </div>
      <?php print render($content['body']); ?>
    </div>
  </div>
</div>
