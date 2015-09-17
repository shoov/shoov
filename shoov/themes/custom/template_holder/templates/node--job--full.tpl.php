<div class="form-results">
  <fieldset>
    <div class="heading">
      <?php print $items['back_link']; ?>
    </div>
  </fieldset>
</div>

<div class="box job-box <?php print $job['job_category_style']; ?>" data-cv-sent="<?php print $job['is_cv_sent']; ?>" ng-controller="AffiliateClickCtrl">

  <div class="job-header">
    <h2 class="box-label"><?php print $job['job_category_label']; ?></h2>

    <?php print $job['job_options']; ?>

    <h1 class="title helvetica"><?php print $title; ?></h1>
    <h2 class="job-number"><?php print render($content['field_ethosia_job_number']); ?></h2>
  </div>

  <!-- info -->
  <div class="info">
    <?php print $job['content']; ?>

    <?php if (!$is_hired): ?>
      <div ng-cloak ng-show="sentCV" class="job-cv-sent ng-cloak"><i class="btn-icon btn-icon-ok glyphicon glyphicon-ok"></i></div>
      <div ng-cloak ng-show="sentCV" class="clearfix ng-cloak"></div>
      <a ng-cloak ng-hide="sentCV" href="javascript:void(0);" onclick="toggleBoxContent(this, 'div.send-resume');" class="btn-send btn-large ng-cloak" title="שלח קורות חיים">
        <span class="first-line">שלח</span>
        <span class="second-line">קורות חיים</span>
      </a>

      <?php print $job['prev_job_link']; ?>
      <?php print $job['next_job_link']; ?>
      <?php print $job['actions_list']; ?>

    <?php else: ?>
      <a class="btn-send btn-large hired">
        <span class="first-line">המשרה</span>
        <span class="second-line">אוישה</span>
      </a>

      <?php print $job['prev_job_link']; ?>
      <?php print $job['next_job_link']; ?>

      <?php print $job['more_jobs_link']; ?>
    <?php endif; ?>
  </div> <!-- /info -->

  <!-- send-resume -->
  <div data-alert="alert" class="send-resume" data-job-url="<?php print $job['share_job_link'];?>" data-nid="<?php print $nid; ?>" layout="job-send-resume-page"></div> <!-- /send-resume -->

  <!-- share-job -->
  <div class="share-job" data-job-url="<?php print $job['share_job_link'];?>" data-nid="<?php print $nid; ?>" data-job-title="<?php print $title; ?>" layout="job-share-job-page" ng-controller="shareJobCtrl">
    <a class="arrow-close" href="javascript:void(0);" onclick="toggleBoxContent(this, 'div.share-job');"><i class="fa fa-angle-up"></i></a>
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
            <td colspan="2"><input type="text" name="linkedin_profile" placeholder="או הדבק לינק לפרופיל הלינדקאין שלו" /></td>
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

    <div class="clearfix"></div>
  </div>
  <!-- /share-job -->

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
  <div class="confirm-message ng-cloak" ng-cloak ng-hide="resume.message">
    המשרה מיועדת לנשים ולגברים כאחד
  </div>
</div>

