<div id="wrapper">

  <?php if (isset($tabs)): ?>
    <div class="tabs">
      <?php print render($tabs); ?>
    </div>
  <?php endif; ?>

  <div class="container container-fixed-position--ie8">
    <div class="row">
    <header id="header">
      <div ng-controller="LoginCtrl">
        <form ng-submit="sendResume(resume)" name="formJobSendResumeAdvance" id="form-resume-top" class="send-resume-advance form-top-switch form-resume form-stylish">
          <fieldset>
            <div class="slide">
              <div class="title">שליחת קו"ח</div>
              <p>*כל השדות הם שדות חובה</p>
              <div class="row">
                <div class="column">
                  <input ng-model="resume.firstname" required ng-change="cleanMessages()" name="firstname" type="text" placeholder="שם פרטי" tabindex="1" />
                </div>
                <div multi-select ng-class="{invalid:invalid_geo}" input-model="resume.geo_areas" button-label="title" item-label="title" tick-property="selected" output-model="resume.geo_areas_selected" default-label="אזור גאוגרפי" keep-default-label="true" helper-elements="" on-open="cleanMessages()" css-button="pseudo-select opener" class="column" tabindex="4"></div>
                <div class="column">
                  <!--[if lte IE 8]>
                  <div class="wrapper-file-upload--ie8">
                    <div class="file-upload">
                      <span class="filename">שליחת קו"ח</span>
                      <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" tabindex="8" value='שליחת קו"ח'/>
                    </div>
                  </div>
                  <div class="form-error-message" ng-show="formJobSendResumeAdvance.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>
                  <![endif]-->
                  <!--[if gt IE 8]>
                  <div class="file-upload">
                    <span class="filename">שליחת קו"ח</span>
                    <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" tabindex="8"/>
                  </div>
                  <div class="form-error-message" ng-show="formJobSendResumeAdvance.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>
                  <![endif]-->
                  <!--[if !IE]> -->
                  <div class="file-upload">
                    <span class="filename">שליחת קו"ח</span>
                    <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" tabindex="8"/>
                  </div>
                  <div class="form-error-message" ng-show="formJobSendResumeAdvance.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>
                  <!-- <![endif]-->
                </div>
              </div>
              <div class="row">
                <div class="column">
                  <input ng-model="resume.lastname" required ng-change="cleanMessages()" type="text" name="lastname" placeholder="שם משפחה" tabindex="2" />
                </div>
                <div class="column">
                  <select ui-select2="{width: '100%', placeholder: 'תפקיד נוכחי'}"  required ng-model="resume.job_categories_selected" data-placeholder="תפקיד נוכחי" tabindex="5">
                    <option value="">בחר תפקיד</option>
                    <optgroup ng-repeat="job_category in resume.job_categories" label="{[{ job_category.title }]}">
                      <option ng-repeat="job_sub_cateogry in job_category.childes" value="{[{ job_sub_cateogry.id }]}">{[{ job_sub_cateogry.title }]}</option>
                    </optgroup>
                  </select>
                </div>
                <div class="column">
                  <input ng-model="resume.linkedin" data-ng-pattern="urlPattern" ng-change="cleanMessages()" name="linkedin" type="text" placeholder="פרופיל ה LinkedIn שלך" tabindex="8" />
                </div>
              </div>
              <div class="row">
                <div class="column">
                  <input ng-model="resume.email" required ng-change="cleanMessages()" type="email" name="email" placeholder="דואר אלקטרוני" tabindex="3" />
                </div>
                <div class="column open-close">
                  <div multi-select  ng-class="{invalid:invalid_role}" input-model="resume.job_role_types" button-label="title" item-label="title" tick-property="selected" output-model="resume.job_role_types_selected" default-label="שלב בקריירה" keep-default-label="true" helper-elements="" on-open="cleanMessages()" css-button="pseudo-select opener" class="column" tabindex="6"></div>
                </div>
                <div class="column">
                  <!--[if lte IE 8]>
                  <input type="checkbox" id="createAlert_checkbox" ng-model="resume.createAlert" name="createAlert"/>
                  <label for="createAlert_checkbox">שלחו לי משרות דומות</label>
                  <![endif]-->
                  <!--[if gt IE 8]>
                  <styled-checkbox ng-model="resume.createAlert" name="createAlert" label="שלחו לי משרות דומות"></styled-checkbox>
                  <![endif]-->
                  <!--[if !IE]> -->
                  <styled-checkbox ng-model="resume.createAlert" name="createAlert" label="שלחו לי משרות דומות"></styled-checkbox>
                  <!-- <![endif]-->
                </div>
              </div>
              <div class="row submit-holder">
                <!--[if lte IE 8]>
                <input type="submit" value="שלח" />
                <![endif]-->
                <!--[if gt IE 8]>
                <div class="submit-holder-container">
                  <input type="submit" ng-hide="formState.submit" value="שלח" tabindex="10" />
                  <div ng-show="formState.loader" class="spinner medium">Sending...</div>
                </div>
                <![endif]-->
                <!--[if !IE]> -->
                <div class="submit-holder-container">
                  <input type="submit" ng-hide="formState.submit" value="שלח" tabindex="10" />
                  <div ng-show="formState.loader" class="spinner medium">Sending...</div>
                </div>
                <!-- <![endif]-->
                <p ng-show="resume.message" class="resume-message" ng-class="{error: resume.message.error}" ng-bind="resume.message.text"></p>
              </div>
            </div>
          </fieldset>
        </form>

        <div class="btn-form-resume-top-wrapper">
          <a href="javascript:void(0);" id="btn-form-resume-top" class="btn-form-toggle btn-form-resume btn-circle btn-open" onclick="toggleTopForms(this, '#form-resume-top');">
            שליחת קו"ח
          </a>
        </div>

        <!-- Registration Form -->
        <form ng-submit="registration(info)" name="formRegistration" ng-controller="RegistrationCtrl" id="form-register-top" class="form-top-switch form-register form-stylish">
          <fieldset>
            <div id="register-top-box" class="slide">
              <div class="title">הרשמה לאתוסיה</div>
              <p>*שדות חובה</p>
              <div class="row">
                <div class="column">
                  <input ng-model="info.email" required name="email" type="email" placeholder="*מייל" tabindex="1" />
                </div>
                <div class="column">
                  <input ng-model="info.firstname" required name="firstname" type="text" placeholder="*שם פרטי" tabindex="3" />
                </div>
                <div class="column open-close">
                  <select ng-model="info.job_role_type_selected" ng-options="job_role_type.title for job_role_type in info.job_role_types" class="single-select" tabindex="7">
                    <option value="">*שלב בקריירה</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="column">
                  <input ng-model="info.password" required name="password" type="password" placeholder="*סיסמא" tabindex="2" />
                </div>
                <div class="column">
                  <input ng-model="info.lastname" required name="lastname" type="text" placeholder="*שם משפחה" tabindex="4" />
                </div>
                <div class="column">
                  <!--[if lte IE 8]>
                  <div class="wrapper-file-upload--ie8">
                    <div class="file-upload">
                      <span class="filename">שליחת קו"ח</span>
                      <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" tabindex="8" value='שליחת קו"ח'/>
                    </div>
                  </div>
                  <div class="form-error-message" ng-show="formRegistration.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>
                  <![endif]-->
                  <!--[if gt IE 8]>
                  <div class="file-upload">
                    <span class="filename">שליחת קו"ח</span>
                    <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" tabindex="8"/>
                  </div>
                  <div class="form-error-message" ng-show="formRegistration.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>
                  <![endif]-->
                  <!--[if !IE]> -->
                  <div class="file-upload">
                    <span class="filename">שליחת קו"ח</span>
                    <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" tabindex="8"/>
                  </div>
                  <div class="form-error-message" ng-show="formRegistration.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>
                  <!-- <![endif]-->
                </div>
              </div>
              <div class="row">
                <div class="column"></div>
                <div class="column open-close">
                  <select ng-model="info.geo_area_selected" ng-options="geo_area.title for geo_area in info.geo_areas" class="single-select" tabindex="5">
                    <option value="">*אזור גאוגרפי</option>
                  </select>
                </div>
                <div class="column">
                  <!--[if lte IE 8]>
                  <input type="checkbox" id="createAlert_checkbox" ng-model="info.createAlert" name="createAlert"/>
                  <label for="createAlert_checkbox">שלחו לי משרות דומות</label>
                  <![endif]-->
                  <!--[if gt IE 8]>
                  <styled-checkbox ng-model="info.createAlert" name="createAlert" label="שלחו לי משרות דומות"></styled-checkbox>
                  <![endif]-->
                  <!--[if !IE]> -->
                  <styled-checkbox ng-model="info.createAlert" name="createAlert" label="שלחו לי משרות דומות"></styled-checkbox>
                  <!-- <![endif]-->
                </div>
              </div>
              <div class="row">
                <div class="column"></div>
                <div class="column">
                  <select ui-select2="{width: '100%', placeholder: 'תפקיד נוכחי'}" ng-model="info.job_categories_selected" data-placeholder="תפקיד נוכחי" tabindex="6">
                    <option value="">*בחר תפקיד</option>
                    <optgroup ng-repeat="job_category in info.job_categories" label="{[{ job_category.title }]}">
                      <option ng-repeat="job_sub_cateogry in job_category.childes" value="{[{ job_sub_cateogry.id }]}">{[{ job_sub_cateogry.title }]}</option>
                    </optgroup>
                  </select>
                </div>
                <div class="column"></div>
              </div>
              <div class="row submit-holder">
                <div class="submit-holder-container">
                  <input ng-hide="formState.submit" type="submit" value="שלח" ng-disabled="info.isRegistering" tabindex="9" />
                  <div ng-show="formState.loader" class="spinner medium">Sending...</div>
                </div>
                <p ng-show="info.message">
                  <p ng-class="{error: info.message.error}" ng-repeat="line in info.message.text" ng-bind="line"></p>
                </p>
              </div>
            </div>

            <div class="btn-form-resume-top-wrapper">
              <a href="javascript:void(0);" class="btn-form-toggle btn-circle btn-close" onclick="toggleTopForms(this, '#form-register-top');">סגור</a>
            </div>
          </fieldset>
        </form>
        <!-- / Registration Form -->

        <!-- Login Form -->
        <form action="#" id="form-login-top" class="form-top-switch form-login form-stylish">
          <fieldset>
            <div id="login-top-box" class="slide" ng-switch="formType">

              <div class="row">
                <div class="col-sm-6 col-sm-offset-3 login-form-centered--ie8" ng-show="formType == 'login'">
                  <div class="row">
                    <div class="col-sm-12"><div class="title">כניסה</div></div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-5 pull-right"><input ng-model="formUser.username" name="username" type="text" placeholder="מייל" tabindex="1" /></div>
                    <div class="col-sm-5 pull-right"><input ng-model="formUser.password" submit-enter="login(formUser)" name="password" type="password" placeholder="סיסמה"  tabindex="2"  /></div>
                    <div class="col-sm-2 pull-right"><a ng-click="login(formUser)" href="" class="btn-icon btn-login" title="כניסה"><i class="fa fa-angle-left" tabindex="3"></i></a></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-5 col-sm-offset-2 forgot-password"><a ng-click="showForgotPassword()" href="" class="forgot-password pull-left" tabindex="4">שכחתי סיסמה</a></div>
                  </div>
                </div>
              </div>

              <table class="login-form" ng-show="formType == 'forgot'">
                <tr>
                  <th><h2>שכחתי סיסמה</h2></th>
                </tr>
                <tr>
                  <td colspan="2"><input ng-model="formUser.email" type="text" placeholder="*מייל" required /></td>
                  <td>
                    <div class="forgot-password-btn-container">
                      <a ng-hide="formState.submit" ng-click="forgotPassword(formUser.email)" href="" class="btn-icon btn-login" title="כניסה"><i class="fa fa-angle-left"></i></a>
                      <div ng-show="formState.loader" class="spinner small"></div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="left" class="forgot-password">
                    <a ng-click="showLogin()" href="" class="forgot-password">התחבר</a>
                  </td>
                  <td></td>
                </tr>
              </table>
            </div>
            <div class="btn-form-resume-top-wrapper">
              <a href="javascript:void(0);" class="btn-form-toggle btn-circle btn-close" onclick="toggleTopForms(this, '#form-login-top');">סגור</a>
            </div>
           </fieldset>
        </form>
        <!-- / Login Form -->

        <div class="header-content ng-cloak" ng-cloak>
          <strong class="logo"><a href="<?php print url('<front>'); ?>">Ethosia. Human Resources</a></strong>
          <div class="header-frame">
            <ul class="top-nav" ng-if="userLogged()">
              <li><span class="count" id="savedJobsCounter" ng-bind="userSavedJobs"></span><a href="<?php print url('my-jobs');?>">המשרות שלי</a></li>
              <li>
                <span class="opener"><img ng-show="user.picture" ng-src="{[{user.picture}]}" width="21" height="20" /><span ng-bind="user.fullname"></span></span>
                <div class="drop">
                  <ul>
                    <li><a href="<?php print url('search', array('query' => array('type' => 'job'))); ?>">משרות חדשות</a></li>
                    <li><a href="<?php print url('my-alerts'); ?>">התראות</a></li>
                    <li><a href="<?php print url('user'); ?>">פרופיל</a></li>
<!--                    <li><a href="--><?php //print url('affiliate'); ?><!--">סטטוס שותפים</a></li>-->
                    <li><a ng-click="logout()" href="">התנתק</a></li>
                  </ul>
                </div>
              </li>
            </ul>
            <ul class="top-nav" ng-if="!userLogged()">
              <li><span class="count" id="savedJobsCounter" ng-bind="userSavedJobs"></span><a href="<?php print url('my-jobs');?>">משרות שמורות בסל</a></li>
              <li class="right-bordered"><a onclick="toggleTopForms(this, '#form-register-top');" href="javascript:void(0);">הרשמה</a></li>
              <li><a onclick="toggleTopForms(this, '#form-login-top');" href="javascript:void(0);">כניסה</a></li>
            </ul>
              <?php print render($page['navigation']); ?>
          </div>
        </div>
    </div>
    <div class="nav-holder" id="SearchBar" ng-controller="SearchBarCtrl">
      <form class="form-search" data-ng-submit="submitSearch()">
        <fieldset>
          <input type="submit" value="Submit">
          <input ng-hide="selectedSearchType.type == 'survey'" id="searchAutocomplete" data-ng-model="searchParams.freeText" name="freeText" type="text" placeholder="חיפוש חופשי" />
        </fieldset>
      </form>
      <div class="navbar" id="navbar">
        <nav>
          <ul id="nav" class="search-nav">
            <span class="search-nav-item" search-bar-job ng-show="selectedSearchType.type == 'job'"></span>
            <span class="search-nav-item" search-bar-company ng-show="selectedSearchType.type == 'company'"></span>
            <span class="search-nav-item" search-bar-category ng-show="selectedSearchType.type == 'adviser' || selectedSearchType.type == 'job_category' || selectedSearchType.type == 'survey'"></span>
            <li class="search-type active">
              <a data-toggle="dropdown" class="placeholder dropdown-toggle" ng-bind="selectedSearchType.title"></a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li ng-repeat="(key,searchType) in searchTypes"><a ng-click="selectSearchType(key)" ng-bind="searchType.title"></a></li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="general-message ng-cloak" ng-cloak ng-class="{'alert-danger': generalMessage.type == 'error', 'alert-warning': generalMessage.type == 'warning'}" ng-show="generalMessage.visible" ng-controller="GeneralMessageCtrl">
      <span ng-bind-html="generalMessage.message"></span>
      <a href="" ng-click="closeMessage()" class="close">x</a>
    </div>
    <div id="messages">
      <?php print $messages; ?>
    </div>

    </header>
      <main id="main" role="main">
          <?php print $breadcrumbs; ?>
          <?php print render($page['content']); ?>
      </main>
    </div>
  </div>

  <!--Footer content-->
  <footer id="footer">
    <div class="container">
      <div class="holder row">
        <?php print $footer_social_networks; ?>
        <?php print $footer_menu; ?>
        <?php print $footer_copyrights; ?>
      </div>
    </div>
  </footer>

</div>

<script type="text/javascript">
  // Prevent touch behaviour for the menus.
  jQuery('#block-menu-menu-ethosia-main .dropdown > a').click(function(){ return false; })
</script>
