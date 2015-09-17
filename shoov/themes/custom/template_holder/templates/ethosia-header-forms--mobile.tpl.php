<form ng-submit="sendResume(resume)" ng-controller="LoginCtrl" name="formJobSendResumeAdvance" id="form-resume-top" class="send-resume-advance form-top-switch form-resume popup">
  <div class="wrapper">
    <div class="main clearfix">
      <div class="close" onclick="toggleTopForms(this, '#form-resume-top');"><a href="#" class="close-icon"></a></div>
      <div class="title">שלח קו״ח</div>

      <input ng-model="resume.firstname" required name="firstname" type="text" placeholder="שם פרטי" class="form-control" />
      <input ng-model="resume.lastname" required name="lastname" type="text" placeholder="שם משפחה" class="form-control" />
      <input ng-model="resume.email" required name="email" type="email" placeholder="מייל" class="form-control" />

      <div class="select">
        <select ng-model="resume.geo_areas_selected" required ng-class="{invalid:invalid_geo}" ng-options="geo_area.title for geo_area in resume.geo_areas" class="single-select form-control">
          <option disabled value="">אזור גאוגרפי</option>
        </select>
        <span class="fa fa-plus"></span>
      </div>

      <div class="select">
        <select ui-select2="{margin-top:10px !important; width: '100%'}" required ng-model="resume.job_categories_selected" ng-class="{invalid:invalid_role}">
          <option disabled value="">תפקיד</option>
          <optgroup ng-repeat="job_category in resume.job_categories" label="{[{ job_category.title }]}">
            <option ng-repeat="job_sub_cateogry in job_category.childes" value="{[{ job_sub_cateogry.id }]}">{[{ job_sub_cateogry.title }]}</option>
          </optgroup>
        </select>
        <span class="fa fa-plus"></span>
      </div>

      <div class="select file-upload">
        <span class="filename">שליחת קו"ח</span>
        <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" />
        <span class="fa fa-caret-left"></span>
      </div>
      <div class="form-error-message" ng-show="formRegistration.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>

      <div class="submit-holder">
        <div class="submit-holder-container">
          <button type="submit" ng-hide="formState.submit" class="btn-icon btn-login pull-left">שלח</button>
          <div ng-show="formState.loader" class="spinner medium">Sending...</div>
        </div>
        <p ng-show="resume.message" class="resume-message" ng-class="{error: resume.message.error}" ng-bind="resume.message.text"></p>
        <div style="width: 100%;" class="general-message ng-scope" ng-class="{'alert-danger': generalMessage.type == 'error', 'alert-warning': generalMessage.type == 'warning'}" ng-show="generalMessage.visible" ng-controller="GeneralMessageCtrl">
          <span ng-bind-html="generalMessage.message" class="ng-binding">קורות חיים נשלחו בהצלחה</span>
          <a style="position: absolute;top: 4px;left: 15px;opacity: 1;" href="" ng-click="closeMessage()" class="close">x</a>
        </div>
      </div>
    </div>
  </div>
</form>

<form ng-submit="registration(info)" name="formRegistration" ng-controller="RegistrationCtrl" id="form-register-top" class="form-top-switch form-register popup">
  <div class="wrapper">
    <div id="register-top-box" class="main clearfix">
      <div class="close" onclick="toggleTopForms(this, '#form-register-top');"><a href="#" class="close-icon"></a></div>
      <div class="title">הרשמה</div>

      <input ng-model="info.email" required name="email" type="email" placeholder="מייל" class="form-control" />
      <input ng-model="info.password" required name="password" type="password" placeholder="סיסמא" class="form-control" />
      <input ng-model="info.firstname" required name="firstname" type="text" placeholder="שם פרטי" class="form-control" />
      <input ng-model="info.lastname" required name="lastname" type="text" placeholder="שם משפחה" class="form-control" />

      <div class="select">
        <select ng-model="info.geo_area_selected" ng-options="geo_area.title for geo_area in info.geo_areas" class="single-select form-control">
          <option disabled value="">אזור גאוגרפי</option>
        </select>
        <span class="fa fa-plus"></span>
      </div>

      <div class="select">
        <select ui-select2="{width: '100%'}" ng-model="info.job_categories_selected">
          <option disabled value="">תפקיד נוכחי</option>
          <optgroup ng-repeat="job_category in info.job_categories" label="{[{ job_category.title }]}">
            <option ng-repeat="job_sub_cateogry in job_category.childes" value="{[{ job_sub_cateogry.id }]}">{[{ job_sub_cateogry.title }]}</option>
          </optgroup>
        </select>
        <span class="fa fa-plus"></span>
      </div>

      <div class="select">
        <select ng-model="info.job_role_type_selected" ng-options="job_role_type.title for job_role_type in info.job_role_types" class="single-select form-control">
          <option value="">שלב בקריירה</option>
        </select>
        <span class="fa fa-plus"></span>
      </div>

      <div class="select file-upload">
        <span class="filename">שליחת קו"ח</span>
        <input accept=".doc,.docx" name="cv" data-ng-model="cv_file" ng-file-select="onFileSelect($files)" type="file" />
        <span class="fa fa-caret-left"></span>
      </div>
      <div class="form-error-message" ng-show="formRegistration.cv.$error.fileExtensions">הקובץ שבחרת אינו בסיומת תקינה.</div>

      <styled-checkbox ng-model="info.createAlert" name="createAlert" label="שלחו לי משרות מתאימות"></styled-checkbox>

      <div class="submit-holder">
        <div class="submit-holder-container">
          <button type="submit" ng-hide="formState.submit" ng-disabled="info.isRegistering" class="btn-icon btn-login pull-left">
            <i class="fa fa-angle-left"></i>
          </button>

          <div ng-show="formState.loader" class="spinner medium">Sending...</div>
        </div>
        <p ng-show="info.message">
        <p ng-class="{error: info.message.error}" ng-repeat="line in info.message.text" ng-bind="line"></p>
        </p>
      </div>

    </div>
  </div>
</form>

<form action="#" ng-controller="LoginCtrl" id="form-login-top" class="form-top-switch form-login popup">
  <div class="wrapper">
    <div class="main clearfix">
      <div class="close" onclick="toggleTopForms(this, '#form-login-top');"><a href="#" class="close-icon"></a></div>
      <div class="title">כניסה</div>
      <form>

        <div class="clearfix">
          <input ng-model="formUser.username" name="username" type="text" placeholder="מייל" tabindex="1" class="form-control" />
          <input ng-model="formUser.password" submit-enter="login(formUser)" name="password" type="password" placeholder="סיסמה" tabindex="2" class="form-control" />
        </div>

        <div class="general-message ng-scope alert-danger" ng-class="{'alert-danger': generalMessage.type == 'error', 'alert-warning': generalMessage.type == 'warning'}" ng-show="generalMessage.visible" ng-controller="GeneralMessageCtrl" style="width:100%; margin-top: 25px;">
          <span ng-bind-html="generalMessage.message" class="ng-binding"></span>
          <a href="" ng-click="closeMessage()" style="position: absolute;top: 2px;left: 12px;opacity: 1;">x</a>
        </div>

        <div class="submit-holder clearfix">
          <a href="#" onclick="toggleTopForms(this, '#form-forgot-password-top');" class="pull-right">שכחתי סיסמה</a>
          <button ng-click="login(formUser)" type="button" class="btn-icon btn-login pull-left">
            <i class="fa fa-angle-left"></i>
          </button>
        </div>
      </form>
    </div>
    <div class="register">לא רשום עדיין לאתוסיה? <a href="#" onclick="toggleTopForms(this, '#form-register-top');" class="pull-left"><b>הרשמה</b></a></div>
  </div>
</form>

<form action="#" ng-controller="LoginCtrl" id="form-forgot-password-top" class="form-top-switch form-login popup">
  <div class="wrapper">
    <div class="main clearfix">
      <div class="close" onclick="toggleTopForms(this, '#form-forgot-password-top');"><a href="#" class="close-icon"></a></div>
      <div class="title">שכחתי סיסמה</div>

      <form>
        <div class="clearfix">
          <input ng-model="formUser.username" name="username" type="text" placeholder="מייל" tabindex="1" class="form-control" />
        </div>

        <div class="general-message ng-scope alert-danger" ng-class="{'alert-danger': generalMessage.type == 'error', 'alert-warning': generalMessage.type == 'warning'}" ng-show="generalMessage.visible" ng-controller="GeneralMessageCtrl" style="width:100%; margin-top: 25px;">
          <span ng-bind-html="generalMessage.message" class="ng-binding" style="padding-left: 26px;display: inline-block;padding-bottom: 6px;"></span>
          <a href="" ng-click="closeMessage()" style="position: absolute;top: 2px;left: 12px;opacity: 1;">x</a>
        </div>

        <div class="submit-holder clearfix">
          <button ng-click="forgotPassword(formUser.username)" type="button" class="btn-icon btn-login pull-left">
            <i class="fa fa-angle-left"></i>
          </button>
        </div>
      </form>

    </div>
  </div>
</form>
