<li class="gray s-h-parent">
  <div class="holder col-sm-8 s-h-column">
    <div class="info">
      <h2><?php print $title; ?></h2>
      <div class="text">
        <?php print render($content['body']); ?>
        <br>
        <?php print $more_details_link; ?>
      </div>
      <?php print $arrow_link; ?>
    </div>
  </div>
  <div class="frame col-sm-4 s-h-column">
    <a href="#" class="btn-high-tech"><?php print render($content['field_industry']); ?></a>
    <div class="image">
      <?php print render($content['field_image']); ?>
    </div>
  </div>
</li>
