<li class="<?php print render($content['field_style_class_name']); ?> s-h-parent">
  <div class="holder col-sm-8 s-h-column">
    <div class="info">
      <h2><?php print $name; ?></h2>
      <div class="text">
        <?php print render($content['description']); ?>
      </div>
      <?php print $arrow_link; ?>
    </div>
  </div>
  <div class="frame col-sm-4 s-h-column">
    <a href="#" class="btn-high-tech"><?php print $name; ?></a>
    <div class="image">
      <?php print render($content['field_image']); ?>
    </div>
  </div>
</li>
