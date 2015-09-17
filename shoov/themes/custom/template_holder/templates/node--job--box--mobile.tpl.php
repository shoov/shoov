<!-- box -->
<div class="box <?php print $style_class_name; ?> box-job" data-job-status="<?php print $job['status']; ?>" data-cv-sent="<?php print $job['is_cv_sent']; ?>" id="node-<?php print $nid;?>">

  <div class="btn-high-tech mobile"><?php print $job['job_category_label']; ?></div>

  <div class="box-overlay-container">
    <div class="box-overlay" ng-if="jobStatus"></div>
    <div class="box-overlay-content" ng-if="jobStatus">
      <table class="box-actions-list">
        <tr>
          <?php print $job['remove_job_link']; ?>
          <td><?php print $page_link; ?></td>
        </tr>
      </table>
    </div>
    <?php print $job['job_options']; ?>
    <div class="box-heading">
      <h2><?php print $title; ?></h2>
      <span class="id"><?php print render($content['field_ethosia_job_number']); ?></span>
    </div>
  </div>

  <!-- job-alert -->
  <div data-alert="alert" class="job-alert" layout="job-alert-grid"
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
  
  <div class="text info">
    <div class="<?php print $text_direction_style; ?>">
      <a href="<?php print $page_link_url; ?>"><?php print $body_short; ?></a>
    </div>
    <br>

    <div class="links-holder ng-cloak" ng-show="jobStatus" ng-cloak>
      <div ng-show="sentCV" class="job-cv-sent"><i class="btn-icon btn-icon-ok glyphicon glyphicon-ok"></i></div>
      <?php if(!$cv_sent): ?>
        <a
          ng-hide="(isMyJobs && !isUserAnonymous) || sentCV"
          href="javascript:void(0);"
          class="btn-send"
          title="שלח"
          onclick="toggleBoxContent(this, 'div.send-resume');">
          שלח
        </a>
      <?php endif; ?>
      <span
        styled-checkbox ng-show="(isMyJobs && !isUserAnonymous) && !sentCV"
        class="my-jobs" layout="square-big" color="<?php print $style_class_name; ?>"
        ng-click="toggleJob(<?php print $nid; ?>)"
        ng-checked=""
        id="job_<?php print $nid; ?>"
        name="job_<?php print $nid; ?>"
        value="<?php print $nid; ?>">
      </span>
      <?php print $job['actions_list']; ?>
    </div>
  </div>

  <!-- send-resume -->
  <div data-alert="alert" data-nid="<?php print $nid; ?>" class="send-resume text" layout="job-send-resume-grid"></div>
  <!-- /send-resume -->

  <div class="confirm-message outer ng-cloak" ng-hide="jobStatus" ng-cloak>
    משרה זו הורדה מהאתר
  </div>
  <div class="confirm-message outer" id="job-saved-<?php print $nid; ?>" style="display: none;"></div>
</div> <!-- /box -->
