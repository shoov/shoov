<li class="s-h-parent">
  <div class="holder col-sm-8 s-h-column">
    <div class="info">
      <h2><?php print $title; ?></h2>
      <div class="date-author">
        <?php if (!empty($content['field_author'])): ?>
          מאת: <?php print render($content['field_author']); ?>
        <?php endif; ?>
        <?php print $date_published; ?>
      </div>
      <div class="text">
        <?php print render($content['body']); ?>
        <br>
        <?php print $more_details_link; ?>
      </div>
      <?php print $arrow_link; ?>
    </div>
  </div>
  <div class="frame col-sm-4 s-h-column">
    <?php if (!empty($article['article_manager'])): ?>
    <div class="btn-high-tech"><?php print $article['article_manager']; ?></div>
    <?php endif; ?>
    <div class="image">
      <?php print render($content['field_image']); ?>
    </div>
  </div>
</li>
