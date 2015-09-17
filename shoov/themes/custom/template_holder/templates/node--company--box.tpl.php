<div class="box">
  <a href="#" class="btn-high-tech"><?php print render($content['field_industry']);?></a>

  <div class="box-overlay-container">
    <div class="box-overlay"></div>
    <div class="box-overlay-content">
      <table class="box-actions-list">
        <tr>
          <td><?php print $page_link; ?></td>
        </tr>
      </table>
    </div>
    <div class="img-holder">
      <?php print render($content['field_image']); ?>
    </div>
  </div>

  <div class="text-holder add-style">
    <h2 class="title"><?php print $title; ?></h2>
    <p>
      <?php print render($content['body']); ?>
    </p>
  </div>
  <?php print $social_networks; ?>
</div>
