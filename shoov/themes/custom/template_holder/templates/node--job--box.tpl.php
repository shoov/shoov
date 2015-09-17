<!-- node--job--box.tpl.php -->
<div class="box <?php print $style_class_name; ?> box-job" data-job-status="<?php print $job['status']; ?>" data-cv-sent="<?php print $job['is_cv_sent']; ?>" id="node-<?php print $nid;?>">

  <div class="btn-high-tech"><?php print $job['job_category_label']; ?></div>

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
      <?php print $body_short; ?>
    </div>
    <br>
    <span ng-if="jobStatus" ng-cloak class="ng-cloak"><?php print $more_details_link; ?></span>

    <div class="links-holder ng-cloak" ng-show="jobStatus" ng-cloak>
      <div ng-show="sentCV" class="job-cv-sent"><i class="btn-icon btn-icon-ok glyphicon glyphicon-ok"></i></div>
      <?php if(!$cv_sent): ?>
        <a ng-hide="(isMyJobs && !isUserAnonymous) || sentCV" href="javascript:void(0);" class="btn-send" title="שלח" onclick="toggleBoxContent(this, 'div.send-resume');">שלח</a>
      <?php endif; ?>
      <span styled-checkbox ng-show="(isMyJobs && !isUserAnonymous) && !sentCV" class="my-jobs" layout="square-big" color="<?php print $style_class_name; ?>" ng-click="toggleJob(<?php print $nid; ?>)" ng-checked="" id="job_<?php print $nid; ?>" name="job_<?php print $nid; ?>" value="<?php print $nid; ?>"></span>
      <?php print $job['actions_list']; ?>
    </div>
  </div>

  <!-- send-resume -->
  <div data-alert="alert" data-nid="<?php print $nid; ?>" class="send-resume text" layout="job-send-resume-grid"></div>
  <!-- /send-resume -->

  <div class="share-job text" data-job-url="<?php print $job['share_job_link'];?>" data-nid="<?php print $nid; ?>" data-job-title="<?php print $title; ?>" layout="job-share-job-grid" ng-controller="shareJobCtrl">
    <a class="arrow-close" onclick="toggleBoxContent(this, 'div.share-job');"><i class="fa fa-angle-up"></i></a>

    <h4>שיתוף משרה</h4>
    <div class="right">
      <p>
        <?php print $share_text; ?>
        <b>בחרו היכן לשתף:</b>
      </p>
      <div class="social-media-box">
        <ul class="social-media-buttons">
          <li><a href="javascript:void(0);"><i class="fa fa-envelope-o btn-icon selected"></i></a></li>
          <li><a href="javascript:void(0);"><i class="fa fa-facebook btn-icon"></i></a></li>
          <li><a href="javascript:void(0);"><i class="fa fa-linkedin btn-icon"></i></a></li>
          <li><a href="javascript:void(0);"><i class="fa fa-twitter btn-icon"></i></a></li>
          <li><a href="javascript:void(0);"><i class="fa fa-google-plus btn-icon"></i></a></li>
        </ul>

        <form class="form-stylish white" action="#">
          <table class="sharing-form">
            <tr>
              <td colspan="2"><input type="text" name="email" placeholder="מייל שלך" /></td>
            </tr>
            <tr>
              <td colspan="2"><input type="text" name="friendEmail" placeholder="מייל של חבר" /></td>
            </tr>
            <tr>
              <td colspan="2">
                <textarea name="message"></textarea>
              </td>
            </tr>
            <tr>
              <td><span class="note">יש <a href="#" class="login-link">להתחבר למערכת</a> כדי לזכות בתגמול</span></td>
              <td><a href="javascript:void(0);" class="btn-send" title="שיתוף">שיתוף</a></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
    <div class="left">
      <p>
        <b>חברים שלכם מקצוענים בתחומי ההייטק והביוטק?</b><br />
        אז למה שלא תהיו חברים טובים?! שלחו אלינו את הקו"ח שלהם,<br />
        ואולי סידרתם להם את המשך הקריירה ולכם 1000&NonBreakingSpace;₪ מתנה.
      </p>

      <form class="form-stylish" action="">
        <table class="form-stylish">
          <tr>
            <td colspan="2"><input type="text" name="email" placeholder="מייל שלך" /></td>
          </tr>
          <tr>
            <td colspan="2"><input type="text" name="friendName" placeholder="שם מלא של החבר" /></td>
          </tr>
          <tr>
            <td colspan="2"><input type="text" name="friendEmail" placeholder="מייל של חבר" /></td>
          </tr>
          <tr>
            <td colspan="2">
              <div class="file-upload"><span class="filename">שליחת קו"ח</span><input type="file"></div>
            </td>
          </tr>
          <tr>
            <td colspan="2"><input type="text" name="linkedin_profile" placeholder="או לינק לפרופיל ה-LinkedIn של" /></td>
          </tr>
          <tr>
            <td colspan="2">
              <a class="add-friend" href="#"><i class="fa fa-plus btn-icon btn-icon-small" title="חבר/ה נוספ/ת"></i>חבר/ה נוספ/ת</a>
            </td>
          </tr>
          <tr>
            <td><span class="note">יש <a href="#" class="login-link">להתחבר למערכת</a> כדי לזכות בתגמול</span></td>
            <td><a href="javascript:void(0);" class="btn-send" title="שלח">שלח</a></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  <div class="confirm-message outer ng-cloak" ng-hide="jobStatus" ng-cloak>
    משרה זו הורדה מהאתר
  </div>
  <div class="confirm-message outer" id="job-saved-<?php print $nid; ?>" style="display: none;"></div>
</div> <!-- /box -->
