<?php

/**
 * @file
 * template.php
 */

/**
 * Implements hook_theme().
 */
function bootstrap_subtheme_theme() {
  $items = array();

  $items['ethosia_content_box'] = array(
    'template' => 'ethosia-content-box',
    'path' => drupal_get_path('theme', 'bootstrap_subtheme') . '/templates',
    'variables' => array(),
  );

  $items['ethosia_boxes_column'] = array(
    'template' => 'ethosia-boxes-column',
    'path' => drupal_get_path('theme', 'bootstrap_subtheme') . '/templates',
    'variables' => array(),
  );

  $items['ethosia_footer'] = array(
    'template' => 'ethosia-footer',
    'path' => drupal_get_path('theme', 'bootstrap_subtheme') . '/templates',
    'variables' => array(),
  );

  $items['ethosia_mobile_header'] = array(
    'template' => 'ethosia-header--mobile',
    'path' => drupal_get_path('theme', 'bootstrap_subtheme') . '/templates',
    'variables' => array(),
  );

  $items['ethosia_mobile_header_forms'] = array(
    'template' => 'ethosia-header-forms--mobile',
    'path' => drupal_get_path('theme', 'bootstrap_subtheme') . '/templates',
    'variables' => array(),
  );

  $items['ethosia_mobile_search_widget'] = array(
    'template' => 'ethosia-search-widget--mobile',
    'path' => drupal_get_path('theme', 'bootstrap_subtheme') . '/templates',
    'variables' => array(),
  );

  return $items;
}

/**
 * Page preprocess.
 */
function bootstrap_subtheme_preprocess_page(&$variables) {
  global $base_url, $user;

  // Theme base path.
  $variables['base_path_theme'] = $base_url . '/' . drupal_get_path('theme', 'bootstrap_subtheme');

  // Export user details.
  if ($user_details = ethosia_general_get_user_details($user->uid)) {
    $image_variables = array(
      'path' => $user_details['picture'],
      'width' => 21,
      'height' => 20,
      'alt' => $user_details['firstname'] . ' ' . $user_details['lastname'],
    );
    $user_details['picture'] = $user_details['picture'] ? theme('image', $image_variables) : '';
  }

  $variables['user_details'] = $user_details;
  $variables['breadcrumbs'] = ethosia_general_breadcrumbs_links();

  $footer_menu = page_element_get_element_details('menu', 'box');
  $footer_copyrights = page_element_get_element_details('rights', 'box');
  $footer_social_networks = page_element_get_element_details('social_networks', 'box');

  $variables['footer_copyrights'] = $footer_copyrights['body'];
  $variables['footer_menu'] = $footer_menu['body'];
  $variables['footer_social_networks'] = $footer_social_networks['body'];

  // Hides local menu tabs if set.
  if (isset($user->hide_tab)) {
    unset($variables['tabs']);
  }

  if (ethosia_general_is_mobile()) {
    $variables['theme_hook_suggestions'][] = 'page__mobile';
    $variables['header_forms'] = theme('ethosia_mobile_header_forms');
    $variables['search_widget'] = theme('ethosia_mobile_search_widget');
    $variables['header'] = theme('ethosia_mobile_header', $variables);
    $variables['theme_hook_suggestions'][] = drupal_is_front_page() ? 'page__homepage__mobile' : 'page__mobile';
  }
}

/**
 * Contact preprocess.
 */
function bootstrap_subtheme_preprocess_contact(&$variables) {
  global $base_url;

  $variables['base_path_ethosia_libraries'] = $base_url . '/' . libraries_get_path('ethosia');
}

/**
 * Contact preprocess.
 */
function bootstrap_subtheme_preprocess_contact_mobile(&$variables) {
  global $base_url;

  // Google maps API.
  drupal_add_js('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
  // Custom config file for the google maps in contact page.
  drupal_add_js(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/contact-mobile.js');

  $variables['base_path_ethosia_libraries'] = $base_url . '/' . libraries_get_path('ethosia');
}

/**
 * Node preprocess.
 */
function bootstrap_subtheme_preprocess_node(&$variables) {
  $node = $variables['node'];
  $view_mode = $variables['view_mode'];

  // Html entities aren't decoded when viewing nodes in boxes and lists. Make
  // sure the title is always decoded.
  $variables['title'] = html_entity_decode($variables['title']);

  // Generic tpl for node--bundle--view-mode.
  $mobile = ethosia_general_is_mobile() ? '__mobile' : '';
  $variables['theme_hook_suggestions'][] = "node__{$node->type}__{$view_mode}{$mobile}";
  $preprocess_function = "bootstrap_subtheme_preprocess_node__{$node->type}__{$view_mode}";
  if (function_exists($preprocess_function)) {
    $preprocess_function($variables);
  }
}

/**
 * Job node preprocess.
 */
function bootstrap_subtheme_preprocess_node__job__full(&$variables) {
  $variables['is_hired'] = !empty($variables['field_is_hired']) ? $variables['field_is_hired'][0]['value'] : FALSE;
}

/**
 * Field preprocess.
 *
 * Override the field tpl with the tpl matched by the bundle machine name
 * and view mode.
 */
function bootstrap_subtheme_preprocess_field(&$variables) {
  $element = $variables['element'];
  $view_mode = 'field__' . $element['#view_mode'];
  $variables['theme_hook_suggestions'][] = $view_mode;
  $bundle_view_mode = 'field__' . $element['#bundle'] . '__' . $element['#view_mode'];
  $variables['theme_hook_suggestions'][] = $bundle_view_mode;
}

/**
 * Preprocess taxonomy term.
 */
function bootstrap_subtheme_preprocess_taxonomy_term(&$variables) {
  $term = $variables['term'];
  $view_mode = $variables['view_mode'];

  // Generic tpl for taxonomy-term--bundle--view-mode.
  $variables['theme_hook_suggestions'][] = "taxonomy_term__{$term->vocabulary_machine_name}__{$view_mode}";
  $preprocess_function = "bootstrap_subtheme_preprocess_taxonomy_term__{$term->vocabulary_machine_name}__{$view_mode}";
  if (function_exists($preprocess_function)) {
    $preprocess_function($variables);
  }
}

/** HTML preprocess.
 *
 * Add conditional CSS for RTL.
 */
function bootstrap_subtheme_preprocess_html(&$variables) {
  global $user;

  // It wasn't simple to override the metatag default title definition with
  // drupal_set_title(). That's why we reset the title here.
  $item = menu_get_item();
  if ($item['path'] == 'search') {
    $title = ethosia_search_get_page_title();
    if ($title) {
      $variables['head_title'] = $title;
    }
  }

  $variables['attributes_array']['ng-app'] = ethosia_general_is_mobile() ? 'ehMobileApp' : 'ehApp';
  $variables['attributes_array']['id'] = ethosia_general_is_mobile() ? 'ng-mobile-app' : 'ng-app';

  drupal_add_css(libraries_get_path('ethosia') . '/css/bootstrap.min.css', array('group' => 300));
  drupal_add_css('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', array('type' => 'external', 'group' => 300));
  drupal_add_css(libraries_get_path('bower_components') . '/isteven-angular-multiselect/angular-multi-select.css', array('group' => 300));

  drupal_add_css(libraries_get_path('ethosia') . '/css/all.css', array('group' => 300));
  drupal_add_css(libraries_get_path('ethosia') . '/css/components.css', array('group' => 300));
  drupal_add_css(libraries_get_path('ethosia') . '/css/style.css', array('group' => 300));
  if (ethosia_general_is_mobile()) {
    drupal_add_css(libraries_get_path('ethosia') . '/css/mobile.css', array('group' => 300));
  }

  drupal_add_css(libraries_get_path('ethosia') . '/css/ie8.css', array(
    'group' => 300,
    'browsers' => array(
      'IE' => 'lte IE 8',
      '!IE' => FALSE,
    ),
    'preprocess' => FALSE,
  ));
  // Always call the ie.css file, The "IF IE" doesn't work on IE 10 & IE 11.
  // The condition is inside the css file.
  drupal_add_css(libraries_get_path('ethosia') . '/css/ie.css', array('group' => 300));

  drupal_add_css(drupal_get_path('theme', 'bootstrap_subtheme') . '/css/style_overrides.css');

  drupal_add_js(libraries_get_path('ethosia') . '/js/jquery.main.js');
  drupal_add_js(libraries_get_path('ethosia') . '/js/scripts.js');
  drupal_add_js(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/placeholders.jquery.min.js', array(
    'scope' => 'footer',
  ));

  $js_contrib_options = array('group' => 400);

  drupal_add_js(libraries_get_path('bower_components') . '/angular/angular.min.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/angular-auth/src/angular-auth.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/angularLocalStorage/src/angularLocalStorage.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/angular-cookies/angular-cookies.min.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/angular-modal/modal.min.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/angularLocalStorage/src/angularLocalStorage.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/danialfarid-angular-file-upload/dist/angular-file-upload.min.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/danialfarid-angular-file-upload/dist/angular-file-upload-html5-shim.min.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/danialfarid-angular-file-upload/dist/angular-file-upload-shim.min.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/isteven-angular-multiselect/angular-multi-select.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/he/he.js', $js_contrib_options);

  $js_ethosia_app = array('group' => 450);

  if (ethosia_general_is_mobile()) {
    drupal_add_js(libraries_get_path('bower_components') . '/eh-mobile-app/dist/eh-mobile-app.js', $js_ethosia_app);
    drupal_add_js(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/mobile-main-menu.js', $js_ethosia_app);
  }
  else {
    drupal_add_js(libraries_get_path('bower_components') . '/eh-app/dist/eh-app.js', $js_ethosia_app);
  }

  // Select2 script.
  drupal_add_css(libraries_get_path('bower_components') . '/select2/select2.css');
  drupal_add_js(libraries_get_path('bower_components') . '/select2/select2.js', $js_contrib_options);
  drupal_add_js(libraries_get_path('bower_components') . '/angular-ui-select2/src/select2.js', $js_contrib_options);

  // Autocomplete script.
  drupal_add_css(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/sAutocomplete/css/sAutocomplete.css');
  drupal_add_js(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/sAutocomplete/js/jquery.base64.js');
  drupal_add_js(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/sAutocomplete/js/sAutocomplete.js');
  drupal_add_js(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/search-autocomplete.js');

  drupal_add_js(drupal_get_path('theme', 'bootstrap_subtheme') . '/js/article-promote.js');

  $ethosia_variables['base_path'] = url('', array('absolute' => TRUE));

  // Add user details object to use in the angular.
  if ($user->uid) {
    $ethosia_variables['user'] = ethosia_general_get_user_details($user->uid);
  }
  else {
    // Anonymous user saved jobs.
    $ethosia_variables['user']['saved_jobs'] = ethosia_job_get_user_saved_jobs();
  }
  $ethosia_variables['user']['csrfToken'] = drupal_get_token('rest');

  // Search variables.
  $ethosia_variables['search'] = array(
    'path' => url('search'),
    'type' => (!empty($_GET['type'])) ? $_GET['type'] : ethosia_search_get_search_bar_type(),
  );

  // Export the terms so we can use it in the angular side in the forms.
  // Export the terms related to jobs.
  $job_category_options = array(
    'style' => 'field_style_class_name',
  );
  $ethosia_variables['job']['fields']['job_categories'] = ethosia_general_vocabulary_terms('job_categories', $job_category_options);
  $ethosia_variables['job']['fields']['geo_areas'] = ethosia_general_vocabulary_terms('geo_areas');
  $ethosia_variables['job']['fields']['job_role_types'] = ethosia_general_vocabulary_terms('job_role_types');
  $ethosia_variables['job']['fields']['job_scopes'] = ethosia_general_vocabulary_terms('job_scope_types');

  // Export the fields related to companies.
  $ethosia_variables['company']['fields']['industry'] = ethosia_general_vocabulary_terms('industry_types');
  $ethosia_variables['company']['fields']['numberOfEmployees'] = ethosia_general_vocabulary_terms('number_of_employees');
  $ethosia_variables['company']['fields']['countries'] = country_get_list();

  // Export the fields related to smart agents.
  $ethosia_variables['smart_agent']['fields']['frequency'] = ethosia_general_vocabulary_terms('smart_agent_frequency');

  drupal_add_js(array('ethosia' => $ethosia_variables), 'setting');
}

/**
 * Bootstrap theme wrapper function for the _menu_ethosia_main menu links.
 */
function bootstrap_subtheme_menu_tree__menu_ethosia_main(&$variables) {
  return '<ul class="subnav">' . $variables['tree'] . '</ul>';
}

/**
 * Mimemail message preprocess.
 */
function bootstrap_subtheme_preprocess_mimemail_message(&$variables) {
  // Messages without fancy html layout.
  if (in_array($variables['key'], array('cv-crm-email', 'user-register-admin', 'add-company-admin'))) {
    $variables['theme_hook_suggestions'] = array('mimemail_message__plain');
    return;
  }

  // Setting the title on the mail header. Not translated on purpose.
  switch ($variables['key']) {
    case 'paid_affiliate_message':
      $variables['header_title'] = 'Congratulations';
      break;

    default:
      $variables['header_title'] = 'Welcome!';
  }

  // Images path is hard coded in order to be able to show images on mails from
  // the blocked test environments.
  $variables['images_path'] = 'http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email';
}

/**
 * Overrides theme_menu_link().
 */
function bootstrap_subtheme_menu_link__menu_ethosia_main(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    // Prevent dropdown functions from being added to management menu so it
    // does not affect the navbar module.
    if (($element['#original_link']['menu_name'] == 'management') && (module_exists('navbar'))) {
      $sub_menu = drupal_render($element['#below']);
    }
    elseif ((!empty($element['#original_link']['depth'])) && ($element['#original_link']['depth'] == 1)) {
      // Add our own wrapper.
      unset($element['#below']['#theme_wrappers']);
      $sub_menu = '<ul class="dropdown-menu dropdown-menu-right">' . drupal_render($element['#below']) . '</ul>';
      // Generate as standard dropdown.
      $element['#attributes']['class'][] = 'dropdown';
      $element['#localized_options']['html'] = TRUE;

      // Set dropdown trigger element to # to prevent inadvertant page loading
      // when a submenu link is clicked.
      $element['#localized_options']['attributes']['data-target'] = '#';
      $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
      $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
    }
  }
  // On primary navigation menu, class 'active' is not set on active menu item.
  // @see https://drupal.org/node/1896674
  if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
    $element['#attributes']['class'][] = 'active';
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Implements theme_status_message().
 */
function bootstrap_subtheme_status_messages($variables) {
  // Hack for easily replacing messages text.
  $replace_messages = array(
    'New identity added.' => t('חיברת את החשבון בהצלחה'),
  );

  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
    'info' => t('Informative message'),
  );

  // Map Drupal message types to their corresponding Bootstrap classes.
  // @see http://twitter.github.com/bootstrap/components.html#alerts
  $status_class = array(
    'status' => 'success',
    'error' => 'danger',
    'warning' => 'warning',
    // Not supported, but in theory a module could send any type of message.
    // @see drupal_set_message()
    // @see theme_status_messages()
    'info' => 'info',
  );

  foreach (drupal_get_messages($display) as $type => $messages) {
    $class = (isset($status_class[$type])) ? ' alert-' . $status_class[$type] : '';
    $output .= "<div class=\"general-message$class\">\n";
    $output .= "  <a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>\n";

    if (!empty($status_heading[$type])) {
      $output .= '<h4 class="element-invisible">' . $status_heading[$type] . "</h4>\n";
    }

    foreach ($messages as &$message) {
      if (is_string($message) && !empty($replace_messages[check_plain($message)])) {
        $message = $replace_messages[check_plain($message)];
      }
    }

    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }

    $output .= "</div>\n";
  }
  return $output;
}
