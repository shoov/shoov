<?php

/**
 * @file
 * Contains \RestfulDataProviderCToolsPlugins
 */

abstract class ShoovDataProviderGitHub extends \RestfulBase implements \ShoovDataProviderGitHubInterface {

  /**
   * The loaded plugins.
   *
   * @var array
   */
  protected $repos = array();

  /**
   * Return the plugins.
   *
   * @return array
   */
  public function getRepos() {
    if ($this->repos) {
      return $this->repos;
    }

    $wrapper = entity_metadata_wrapper('user', $this->getAccount());
    $access_token = $wrapper->field_github_access_token->value();

    $options = array(
      'headers' => array(
        'Authorization' => 'token ' . $access_token,
      ),
    );

    $result = $this->httpRequestGithub('https://api.github.com/user/repos', $options);
    $data = drupal_json_decode($result->data);

    $this->repos = $data;
    return $this->repos;
  }

  /**
   * Performs an HTTP request to GitHub and check for errors.
   *
   * @param string $url
   *   A string containing a fully qualified URI.
   * @param array $options
   *   Options array as passed to drupal_http_request().
   *
   * @return object
   *   The result object.
   *
   * @see drupal_http_request().
   */
  protected function httpRequestGithub($url, $options) {
    $result = drupal_http_request($url, $options);
    $this->checkGitHubHttpError($url, $result);
    return $result;
  }


  /**
   * Check if an error was returned by Github, and if so throw an exception.
   *
   * GitHub might return a 200 code, but the data is in fact an error.
   *
   * @param string $url
   *   The URL sent to GitHub
   * @param $result
   *   The result object from the drupal_http_request() call.
   *
   * @throws \RestfulServerConfigurationException
   */
  protected function checkGitHubHttpError($url, $result) {
    if (intval($result->code) !== 200 || strpos($result->data, 'error=') === 0) {

      $params = array(
        '@url' => $url,
        '@code' => $result->code,
        '@error' => $result->data,
      );

      throw new \RestfulServerConfigurationException(format_string('Calling @url resulted with a @code HTTP code, with the following error message: @error', $params));
    }
  }

  /**
   * Get the valid result from the response of the HTTP request.
   *
   * Result format is for example:
   * 'access_token=someTokenValue&scope=&token_type=bearer';
   *
   * @param $result
   *   The result object from the drupal_http_request() call.
   *
   * @return string
   *   The result.
   */
  protected function getDataFromHttpResult($result) {
    $return = $result->data;

    $return = explode('&', $result->data);
    $return = explode('=', $return[0]);
    return $return[1];
  }

  /**
   * Gets the plugins filtered and sorted by the request.
   *
   * @return array
   *   Array of plugins.
   */
  public function getReposSortedAndFiltered() {
    $plugins = $this->getRepos();
    $public_fields = $this->getPublicFields();

    foreach ($this->parseRequestForListFilter() as $filter) {
      foreach ($plugins as $plugin_name => $plugin) {
        // Initialize to TRUE for AND and FALSE for OR (neutral value).
        $match = $filter['conjunction'] == 'AND';
        for ($index = 0; $index < count($filter['value']); $index++) {
          $property = $public_fields[$filter['public_field']]['property'];

          if (empty($plugin[$property])) {
            // Property doesn't exist on the plugin, so filter it out.
            unset($plugins[$plugin_name]);
          }

          if ($filter['conjunction'] == 'OR') {
            $match = $match || $this->evaluateExpression($plugin[$property], $filter['value'][$index], $filter['operator'][$index]);
            if ($match) {
              break;
            }
          }
          else {
            $match = $match && $this->evaluateExpression($plugin[$property], $filter['value'][$index], $filter['operator'][$index]);
            if (!$match) {
              break;
            }
          }
        }
        if (!$match) {
          // Property doesn't match the filter.
          unset($plugins[$plugin_name]);
        }
      }
    }


    if ($this->parseRequestForListSort()) {
      uasort($plugins, array($this, 'sortMultiCompare'));
    }

    return $plugins;
  }

  /**
   * Overrides \RestfulBase::isValidConjuctionForFilter().
   */
  protected static function isValidConjunctionForFilter($conjunction) {
    $allowed_conjunctions = array(
      'AND',
      'OR',
    );

    if (!in_array(strtoupper($conjunction), $allowed_conjunctions)) {
      throw new \RestfulBadRequestException(format_string('Conjunction "@conjunction" is not allowed for filtering on this resource. Allowed conjunctions are: !allowed', array(
        '@conjunction' => $conjunction,
        '!allowed' => implode(', ', $allowed_conjunctions),
      )));
    }
  }

  /**
   * Evaluate a simple expression.
   *
   * @param $value1
   *   The first value.
   * @param $value2
   *   The second value.
   * @param $operator
   *   The operator.
   *
   * @return bool
   *   TRUE or FALSE based on the evaluated expression.
   *
   * @throws RestfulBadRequestException
   */
  protected function evaluateExpression($value1, $value2, $operator) {
    switch($operator) {
      case '=':
        return $value1 == $value2;

      case '<':
        return $value1 < $value2;

      case '>':
        return $value1 > $value2;

      case '>=':
        return $value1 >= $value2;

      case '<=':
        return $value1 <= $value2;

      case '<>':
      case '!=':
        return $value1 != $value2;

      case 'IN':
        return in_array($value1, $value2);

      case 'BETWEEN':
        return $value1 >= $value2[0] && $value1 >= $value2[1];
    }
  }

  /**
   * Sort plugins by multiple criteria.
   *
   * @param $value1
   *   The first value.
   * @param $value2
   *   The second value.
   *
   * @return int
   *   The values expected by uasort() function.
   *
   * @link http://stackoverflow.com/a/13673568/750039
   */
  protected function sortMultiCompare($value1, $value2) {
    $sorts = $this->parseRequestForListSort();
    foreach ($sorts as $key => $order){
      if ($value1[$key] == $value2[$key]) {
        continue;
      }

      return ($order == 'DESC' ? -1 : 1) * strcmp($value1[$key], $value2[$key]);
    }

    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getTotalCount() {
    return count($this->getReposSortedAndFiltered());
  }

  public function index() {
    $return = array();

    foreach (array_keys($this->getReposSortedAndFiltered()) as $plugin_name) {
      $return[] = $this->view($plugin_name);
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   *
   * @todo: We should generalize this, as it's repeated often.
   */
  public function view($id) {
    $cache_id = array(
      'id' => $id,
    );
    $cached_data = $this->getRenderedCache($cache_id);
    if (!empty($cached_data->data)) {
      return $cached_data->data;
    }

    $repo = $this->repos[$id];

    // Loop over all the defined public fields.
    foreach ($this->getPublicFields() as $public_field_name => $info) {
      $value = NULL;

      if ($info['create_or_update_passthrough']) {
        // The public field is a dummy one, meant only for passing data upon
        // create or update.
        continue;
      }

      // If there is a callback defined execute it instead of a direct mapping.
      if ($info['callback']) {
        $value = static::executeCallback($info['callback'], array($repo));
      }
      // Map row names to public properties.
      elseif ($info['property']) {
        $value = $repo->$info['property'];
      }

      // Execute the process callbacks.
      if ($value && $info['process_callbacks']) {
        foreach ($info['process_callbacks'] as $process_callback) {
          $value = static::executeCallback($process_callback, array($value));
        }
      }

      $output[$public_field_name] = $value;
    }

    $this->setRenderedCache($output, $cache_id);
    return $output;
  }
}
