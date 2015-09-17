<div class="box blog">
  <h2><a href="<?php print $articles_url;?>" class="btn-high-tech"><?php print $term_name; ?></a></h2>

  <div class="img-holder">
    <?php print render($content['field_image']); ?>
  </div>

  <div class="text-holder add-style">
    <strong class="title"><?php print render($content['description']); ?></strong>
    <?php print render($links); ?>
  </div>

</div>
