<header id="header" class="mobile-header">

  <?php print $header_forms; ?>

  <div class="nav-holder">
    <strong class="mobile-logo"><a href="<?php print url(''); ?>"></a></strong>

    <div class="navbar navbar-default mobile">
      <input type="checkbox" id="active-menu" class="hidden-menu-ticker">
      <label class="btn-menu">
        <span class="sr-only">Toggle navigation</span>
      </label>

      <div data-toggle="collapse" data-target="#search-menu" class="search-job-icon collapsed"></div>

      <div class="mobile-menu" data-status="closed">
        <span class="user">
          <?php if (!empty($user_details)): ?>
            <?php print $user_details['picture']; ?>
            <a href="<?php print url('user'); ?>"><?php print $user_details['fullname']; ?><b class="caret"></b></a>
          <?php else: ?>
            <a href="#" onclick="toggleTopForms(this, '#form-login-top');">כניסה</a>
          <?php endif; ?>
        </span>
        <?php if (!empty($user_details)): ?>
          <ul>
            <li><a href="<?php print url('my-jobs'); ?>"><?php print ($user_details['saved_jobs']); ?><?php print t(' משרות שמורות'); ?></a></li>
<!--            <li><a href="--><?php //print url('my-alerts'); ?><!--">--><?php //print t('התראות'); ?><!--</a></li>-->
            <li><a href="<?php print url('user'); ?>"><?php print t('פרופיל'); ?></a></li>
            <li><a href="<?php print url('user/logout'); ?>"><?php print t('התנתק'); ?></a></li>
          </ul>
        <?php endif; ?>
        <ul>
          <li class="homepage-title"><a href="<?php print url('<front>'); ?>"><?php print t('עמוד בית'); ?></a></li>
          <li><a href="#" onclick="toggleTopForms(this, '#form-resume-top');"><?php print t('שלח קו"ח'); ?></a></li>
          <li class="" data-toggle="collapse" data-target="#sidebar-about-us">
            <span><?php print t('אודות'); ?></span><span class="toggle-sign pull-left">+</span>
          </li>
          <li id="sidebar-about-us" class="collapse">
            <ul>
              <li><a href="<?php print url('about_us'); ?>">מי אנחנו</a></li>
              <li><a href="<?php print url('job-categories'); ?>">תחומי גיוס</a></li>
              <li><a href="<?php print url('node/560364'); ?>" title="">השירותים שלנו</a></li>
<!--              <li><a href="--><?php //print url('managers'); ?><!--" title="">הנהלת אתוסיה</a></li>-->
<!--              <li><a href="--><?php //print url('advisers'); ?><!--">צוות היועצים</a></li>-->
              <li><a href="<?php print url('view/client_recommendations'); ?>" title="">לקוחות ממליצים</a></li>
            </ul>
          </li>
          <li><a href="<?php print url('salary_report'); ?>"><?php print t('סקר שכר'); ?></a></li>
          <li><a href="<?php print url('companies'); ?>"><?php print t('אינדקס חברות'); ?></a></li>
          <li class="no-border"><a href="<?php print url('contact'); ?>"><?php print t('צור קשר'); ?></a></li>
        </ul>
      </div>
    </div>

    <?php print $search_widget; ?>

</header>
