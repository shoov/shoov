<div class="box blue-global">
  <div class="btn-high-tech">
    <?php print t('התראה'); ?>
  </div>
  <?php print $action_list; ?>
  <div class="box-heading"><h2 class="alert-title"><?php print $title;?></h2></div>
  <div class="text info my-alert">
    <div>
      <fieldset class="description">
        <div class="option">
          <div class="column title">תחום</div>
          <div class="column" title="<?php print $alert['edit']['job_categories']['title']; ?>"><?php print $alert['edit']['job_categories']['title']; ?></div>
        </div>
        <div class="option">
          <div class="column title">תפקיד</div>
          <div class="column description" title="<?php print $alert['edit']['job_sub_categories']['title']; ?>"><?php print $alert['edit']['job_sub_categories']['title']; ?></div>
        </div>
        <div class="option">
          <div class="column title">אזור</div>
          <div class="column description" title="<?php print $alert['edit']['geo_areas']['title']; ?>"><?php print $alert['edit']['geo_areas']['title']; ?></div>
        </div>
      </fieldset>
      <fieldset class="description">
        <div class="option">
          <div class="column title">דרג</div>
          <div class="column description" title="<?php print $alert['edit']['job_role_types']['title']; ?>"><?php print $alert['edit']['job_role_types']['title']; ?></div>
        </div>
        <div class="option">
          <div class="column title">היקף</div>
          <div class="column description" title="<?php print $alert['edit']['job_scope']['title']; ?>"><?php print $alert['edit']['job_scope']['title']; ?></div>
        </div>
        <div class="option">
          <div class="column title">תדירות ההתראות</div>
          <div class="column description no-overflow"><?php print $alert['edit']['frequency']['title']; ?></div>
        </div>
      </fieldset>
    </div>
    <div class="clearfix"></div>
    <?php print $alert['show_jobs_link']; ?>
  </div>

  <div class="text edit my-alert">
    <form ng-submit="updateAlert(alert)" class="form-stylish" name="formEditAlert"
      data-nid="<?php print $nid; ?>"
      data-email="<?php print $alert['edit']['email']; ?>"
      data-job-categories-id="<?php print $alert['edit']['job_categories']['id']; ?>"
      data-job-categories-title="<?php print $alert['edit']['job_categories']['title']; ?>"
      data-job-sub-categories-id="<?php print $alert['edit']['job_sub_categories']['id']; ?>"
      data-job-sub-categories-title="<?php print $alert['edit']['job_sub_categories']['title']; ?>"
      data-geo-areas-id="<?php print $alert['edit']['geo_areas']['id']; ?>"
      data-geo-areas-title="<?php print $alert['edit']['geo_areas']['title']; ?>"
      data-job-scope-id="<?php print $alert['edit']['job_scope']['id']; ?>"
      data-job-scope-title="<?php print $alert['edit']['job_scope']['title']; ?>"
      data-job_role_types-id="<?php print $alert['edit']['job_role_types']['id']; ?>"
      data-job_role_types-title="<?php print $alert['edit']['job_role_types']['title']; ?>"
      data-frequency="<?php print $alert['edit']['frequency']['id']; ?>"
      my-alert-edit>
      <table class="form-stylish">
        <tr>
          <td><input ng-model="alert.email" ng-change="cleanMessages()" required name="email" type="email" placeholder="מייל"/></td>
        </tr>
        <tr>
          <td>
            <select ng-model="alert.job_categories_selected" ng-options="job_category.title for job_category in alert.job_categories" required name="job_categories" class="single-select">
              <option value="">תחום</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <input ng-hide="alert.job_categories_selected" disabled type="text" placeholder="תפקיד"/>
            <div ng-show="alert.job_categories_selected" multi-select input-model="alert.job_sub_categories" output-model="alert.job_sub_categories_selected" button-label="title" item-label="title" tick-property="selected" default-label="תפקיד" helper-elements="" hide-caret-icon="true" on-open="cleanMessages()" css-button="pseudo-select opener" class="column"></div>
          </td>
        </tr>
        <tr>
          <td>
            <div multi-select input-model="alert.geo_areas" output-model="alert.geo_areas_selected_<?php print $nid; ?>" button-label="title" item-label="title" tick-property="selected" default-label="אזור" helper-elements="" hide-caret-icon="true" on-open="cleanMessages()" css-button="pseudo-select opener" class="column"></div>
          </td>
        </tr>
        <tr>
          <td>
            <select ng-model="alert.job_scopes_selected" ng-options="scope.title for scope in alert.job_scopes" required name="job_scopes" class="single-select">
              <option value="">היקף המשרה<option/>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <select ng-model="alert.job_role_types_selected" ng-options="rol.title for rol in alert.job_role_types" required name="job_role_types" class="single-select">
              <option value="">שלב בקריירה</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <span class="frequency frequency-title">תדירות</span>
            <span class="frequency frequency-group-radio">
              <span ng-repeat="frequency in frequencies" class="frequency">
                <input ng-model="alert.frequency" type="radio" name="frequency_<?php print $nid; ?>" value="{[{frequency.id}]}" id="<?php print $nid; ?>_frequency-{[{$index+1}]}" /> <label for="<?php print $nid; ?>_frequency-{[{$index+1}]}"><span></span>{[{frequency.title}]}</label>
              </span>
            </span>
          </td>
        </tr>
      </table>
      <p class="alert-error-message" ng-show="alert.message" ng-class="{error: alert.message.error}">{[{alert.message.text}]}</p>
      <input type="submit" class="btn-send" value="שלח" />
    </form>
  </div>

</div>
