<div class="box <?php print render($content['field_style_class_name']); ?>">
  <?php print $name_link; ?>
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
</div>
