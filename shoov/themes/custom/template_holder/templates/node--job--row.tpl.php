<li class="<?php print $style_class_name; ?> s-h-parent box-job" data-job-status="<?php print $job['status']; ?>" data-cv-sent="<?php print $job['is_cv_sent']; ?>" id="node-<?php print $nid;?>">
  <div class="holder col-sm-8 s-h-column">

    <!-- info -->
    <div class="info">
      <h3><?php print t('התפקיד'); ?></h3>
      <p>
        <div class="<?php print $text_direction_style; ?>">
          <?php print $body_short; ?>
        </div>
        <br>
        <span ng-show="jobStatus" ng-cloak class="ng-cloak"><?php print $more_details_link; ?></span>
      </p>
      <div ng-show="jobStatus" ng-cloak class="ng-cloak">
        <?php print $job['actions_list']; ?>
        <div ng-show="sentCV" class="job-cv-sent"><i class="btn-icon btn-icon-ok glyphicon glyphicon-ok"></i></div>
        <a ng-hide="(isMyJobs && !isUserAnonymous) || sentCV" href="javascript:void(0);" class="btn-send" onclick="toggleRowContent(this, 'div.send-resume');" title="שלח">שלח</a>
        <span styled-checkbox ng-show="(isMyJobs && !isUserAnonymous) && !sentCV" class="my-jobs list" layout="square-big" color="<?php print $style_class_name; ?>" ng-click="toggleJob(<?php print $nid; ?>)" ng-checked="" id="job_<?php print $nid; ?>" name="job_<?php print $nid; ?>" value="<?php print $nid; ?>"></span>
      </div>
    </div> <!-- /info -->


    <!-- send-resume -->
    <div data-alert="alert" data-nid="<?php print $nid; ?>" class="send-resume" layout="job-send-resume-list"></div> <!-- /send-resume -->

    <!-- share-job -->
    <div class="share-job" data-job-url="<?php print $job['share_job_link'];?>" data-nid="<?php print $nid; ?>" data-job-title="<?php print $title; ?>"  layout="job-share-job-list" ng-controller="shareJobCtrl">
      <a class="arrow-close" onclick="toggleRowContent(this, 'div.share-job');"><i class="fa fa-angle-up"></i></a>
      <h2>שתף משרה!</h2>
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
                <td><span class="note">על מנת לקבל תגמול עליך <a href="#" class="login-link">להתחבר למערכת</a></span></td>
                <td><a href="javascript:void(0);" class="btn-send">שלח</a></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
      <div class="left">
        <p>
          <b>יש לך חבר שתפור על המשרה הזו?</b><br />
          שלח לנו את הפרטים שלו, ובמידה והוא יתקבל,<br />
          אתה תרוויח 1000 ש"ח.
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
                <div class="file-upload"><span class="filename">העלה קו"ח של החבר</span><input type="file"></div>
              </td>
            </tr>
            <tr>
              <td colspan="2"><input type="text" name="linkedin_profile" placeholder="או הדבק לינק לפרופיל הלינדקאין שלו" /></td>
            </tr>
            <tr>
              <td colspan="2">
                <a class="add-friend" href="#"><i class="fa fa-plus btn-icon btn-icon-small"></i>חבר נוסף</a>
              </td>
            </tr>
            <tr>
              <td><span class="note">על מנת לקבל תגמול עליך <a href="#" class="login-link">להתחבר למערכת</a></span></td>
              <td><a href="javascript:void(0);" class="btn-send">שלח</a></td>
            </tr>
          </table>
        </form>
      </div>
    </div> <!-- /share-job -->

    <!-- job-alert -->
    <div data-alert="alert" class="job-alert" layout="job-alert-list"
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

    <div class="confirm-message ng-cloak" ng-hide="jobStatus" ng-cloak>
      משרה זו הורדה מהאתר
    </div>
    <div class="confirm-message" id="job-saved-<?php print $nid; ?>" style="display: none;"></div>
  </div>

  <div class="frame col-sm-4 s-h-column">
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
      <h2><?php print $title; ?></h2>
      <span class="id"><?php print render($content['field_ethosia_job_number']); ?></span>
    </div>
  </div>
</li>
