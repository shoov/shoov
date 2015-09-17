<div class="box">
  <?php if (!empty($article['article_manager'])): ?>
  <div class="btn-high-tech"><?php print $article['article_manager']; ?></div>
  <?php endif; ?>

  <div class="box-overlay-container">
    <div class="box-overlay"></div>
    <div class="box-overlay-content">
      <table class="box-actions-list">
        <tr>
          <td>
            <?php print $page_link; ?>
          </td>
        </tr>
      </table>
    </div>
    <div class="img-holder">
      <?php print render($content['field_image']); ?>
    </div>
  </div>

  <div class="text-holder add-style">
    <h2 class="title"><?php print $title; ?></h2>
    <div class="date-author">
      <?php if (!empty($content['field_author'])): ?>
        מאת: <?php print render($content['field_author']); ?>
      <?php endif; ?>
      <?php print $date_published; ?>
    </div>
    <p>
      <?php print render($content['body']); ?>
    </p>
  </div>
  <?php print $social_networks; ?>
</div>
