<li class="s-h-parent">
  <div class="holder col-sm-8 s-h-column">
    <div class="info">
      <h2><?php print $name; ?></h2>
      <div class="text">
        <?php print render($content['description']); ?>
        <?php print $more_details_link; ?>
      </div>
      <?php print render($links); ?>
      <?php print $arrow_link; ?>
    </div>
  </div>
  <div class="frame col-sm-4 s-h-column">
    <h2><a href="<?php print $articles_url;?>" class="btn-high-tech"><?php print $term_name; ?></a></h2>
    <div class="image">
      <?php print render($content['field_image']); ?>
    </div>
  </div>
</li>
