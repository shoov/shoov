<div class="form-results">
  <div class="heading">
    <?php print $items['back_link']; ?>
  </div>
</div>

<div class="box company-box">
  <div class="box-label"><?php print render($content['field_industry']); ?></div>

  <?php print $social_networks; ?>

  <div class="box-inside">
    <div class="image">
      <?php print render($content['field_image']);?>
    </div>
    <div class="description">
      <div class="company-info">
        <h1 class="title"><?php print $title; ?></h1>

        <fieldset>
          <?php if (!empty($company['address'])): ?>
          <div><b>כתובת</b> <?php print $company['address']; ?></div>
          <?php endif; ?>

          <?php if (!empty($company['employees'])): ?>
          <div><b>מס' עובדים</b> <?php print $company['employees']; ?></div>
          <?php endif; ?>
        </fieldset>

        <fieldset>
          <?php if (!empty($company['year'])): ?>
          <div><b>שנת יסוד</b> <?php print $company['year']; ?></div>
          <?php endif; ?>

          <?php if (!empty($company['site_url'])): ?>
          <div><b>אתר</b> <?php print $company['site_url']; ?></a></div>
          <?php endif; ?>
        </fieldset>

      </div>

      <div class="company-description">
        <?php print render($content['body']); ?>
        <?php print $wrong_link; ?>
      </div>
    </div>
  </div>
</div>
