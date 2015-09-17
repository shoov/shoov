<li class="s-h-parent">
  <div class="holder col-sm-8 s-h-column">
    <div class="info">
      <h2><?php print $title; ?></h2>
      <p>
        <?php print render($content['body']); ?>
      </p>

      <ul class="actions-list icon-buttons">
        <li><a href="#" title="עקוב בלינקדאין"><i class="fa fa-linkedin btn-icon"></i></a></li>
        <li><a href="#" title="עקוב בפייסבוק"><i class="fa fa-facebook btn-icon"></i></a></li>
      </ul>
      <a href="#" class="btn-send btn-arrow"><span>&gt;</span></a>
    </div>
  </div>
  <div class="frame image col-sm-4 s-h-column">
    <div class="image">
      <?php print render($content['field_image']); ?>
    </div>
  </div>
</li>
