<div class="form-results">
  <div class="heading">
    <?php print $items['back_link']; ?>
  </div>
</div>

<div class="box industry-box <?php print render($content['field_style_class_name']); ?>">
  <h1 class="box-label"><?php print $name; ?></h1>
  <div class="box-inside">
    <div class="image">
      <?php print render($content['field_image']); ?>
    </div>
    <div class="description">
      <?php print $description; ?>
      <div class="links-holder">
        <?php print $search_jobs_link; ?>
        <?php print $actions_list; ?>
      </div>
    </div>
  </div>
</div>
