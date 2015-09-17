<div class="box job-box clearfix <?php print $job['job_category_style']; ?>" data-cv-sent="<?php print $job['is_cv_sent']; ?>" ng-controller="AffiliateClickCtrl">

  <div class="job-header">
    <h2 class="category-label"><?php print $job['job_category_label']; ?></h2>

    <?php print $job['job_options']; ?>

    <h1 class="title helvetica"><?php print $title; ?></h1>
    <h2 class="job-number"><?php print render($content['field_ethosia_job_number']); ?></h2>
  </div>

  <!-- info -->
  <div class="info clearfix">
    <div class="<?php print $job['body_class']; ?>">
      <?php print $job['body']; ?>
    </div>

    <?php if (!$is_hired): ?>
      <?php print $job['actions_list']; ?>

      <div ng-cloak ng-show="sentCV" class="job-cv-sent ng-cloak"><i class="btn-icon btn-icon-ok glyphicon glyphicon-ok"></i></div>
      <div ng-cloak ng-show="sentCV" class="clearfix ng-cloak"></div>
      <a ng-cloak ng-hide="sentCV" href="javascript:void(0);" onclick="toggleBoxContent(this, 'div.send-resume');" class="btn-send btn-large ng-cloak" title="שלח קורות חיים">שלח</a>

    <?php else: ?>
      <?php print $job['more_jobs_link']; ?>

      <a class="btn-send btn-large hired">
        <span class="first-line">המשרה</span>
        <span class="second-line">אוישה</span>
      </a>
    <?php endif; ?>
  </div>
  <!-- /info -->

  <!-- send-resume -->
  <div data-alert="alert" class="send-resume" data-job-url="<?php print $job['share_job_link'];?>" data-nid="<?php print $nid; ?>" layout="job-send-resume-page"></div>
  <!-- /send-resume -->

  <!-- job-alert -->
  <div data-alert="alert" class="job-alert" layout="job-alert-page"
       data-nid="<?php print $nid; ?>"
       data-job-category-title="<?php print $job['alert']['job_category']['title']; ?>"
       data-job-category-id="<?php print $job['alert']['job_category']['id']; ?>"
       data-job-sub-category-id="<?php print $job['alert']['job_sub_categories']['id']; ?>"
       data-job-sub-category-title="<?php print $job['alert']['job_sub_categories']['title']; ?>"
       data-geo-area-title="<?php print $job['alert']['geo_area']['title']; ?>"
       data-geo-area-id="<?php print $job['alert']['geo_area']['id']; ?>"
       data-job-role-types-title="<?php print $job['alert']['job_role_types']['title']; ?>"
       data-job-role-types-id="<?php print $job['alert']['job_role_types']['id']; ?>"
       is-alert-valid="isAlertValid">
  </div>
  <!-- /job-alert -->
</div>
