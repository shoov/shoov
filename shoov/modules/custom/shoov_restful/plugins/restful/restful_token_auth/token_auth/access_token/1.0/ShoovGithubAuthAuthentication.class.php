<?php

/**
 * @file
 * Contains ShoovGithubAuthAuthentication.
 */

class ShoovGithubAuthAuthentication extends \RestfulAccessTokenAuthentication {

  /**
   * Overrides \RestfulBase::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        // Get or create a new user.
        \RestfulInterface::POST => 'getUser',
      ),
    );
  }

  /**
   * Get a user from GitHub.
   *
   * @return array
   *   Array from RESTful token authentication resource.
   *
   * @throws \RestfulUnauthorizedException
   */
  protected function getUser() {
    $request = $this->getRequest();
    if (empty($request['code'])) {
      throw new \RestfulUnauthorizedException('code property is missing.');
    }

    $options = array(
      'method' => 'POST',
      'data' => http_build_query(array(
        'client_id' => variable_get('shoov_github_client_id'),
        'client_secret' => variable_get('shoov_github_client_secret'),
        'code' => $request['code'],
      )),
    );

    $result = $this->httpRequestGithub('https://github.com/login/oauth/access_token', $options);

    $access_token = $this->getDataFromHttpResult($result);

    $options = array(
      'headers' => array(
        'Authorization' => 'token ' . $access_token,
      ),
    );

    $result = $this->httpRequestGithub('https://api.github.com/user', $options);

    $data = drupal_json_decode($result->data);
    $name = $data['login'];

    // Get the username from Github and compare with ours.
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'user')
      ->propertyCondition('name', $name)
      ->range(0, 1)
      ->execute();

    if (empty($result['user'])) {
      // Create a new user.
      $account = $this->createUser($data, $request);
    }
    else {
      $id = key($result['user']);
      $account = user_load($id);
    }

    if ($account->status == FALSE) {
      // User is blocked.
      throw new \RestfulUnauthorizedException('You are blocked from the site.');
    }

    $this->setAccount($account);
    return $this->getOrCreateToken();
  }

  /**
   * Create a new user.
   *
   * @param array $data
   *   Array with the user's data from GitHub
   * @param array $options
   *   Options array as passed to drupal_http_request().
   *
   * @return \stdClass
   *   The newly saved user object.
   */
  protected function createUser($data, $options) {
    $fields = array(
      'name' => $data['login'],
      'mail' => $this->getEmail($options),
      'pass' => user_password(8),
      'status' => TRUE,
      'roles' => array(
        DRUPAL_AUTHENTICATED_RID => 'authenticated user',
      ),
    );

    // The first parameter is left blank so a new user is created.
    $account = user_save('', $fields);
    return $account;
  }

  /**
   * Get user's primary email.
   *
   * @param array $options
   *   Options array as passed to drupal_http_request().
   *
   * @return string
   *   The user's email.
   */
  protected function getEmail($options) {
    $result = $this->httpRequestGithub('https://api.github.com/user/emails', $options);
    foreach (drupal_json_decode($result->data) as $row) {
      if (empty($row['primary'])) {
        // Not a primary email.
        continue;
      }

      return $row['email'];
    }
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
    $this->checkGitHubHttpError($result);
    return $result;
  }


  /**
   * Check if an error was returned by Github, and if so throw an exception.
   *
   * GitHub might return a 200 code, but the data is in fact an error.
   *
   * @param $result
   *   The result object from the drupal_http_request() call.
   *
   * @throws \RestfulServerConfigurationException
   */
  protected function checkGitHubHttpError($result) {
    if (intval($result->code) !== 200 || strpos($result->data, 'error=') === 0) {

      $params = array(
        '@code' => $result->code,
        '@error' => $result->data,
      );

      watchdog('test', format_string('Got error code @code from GitHub, with the following error message: @error', $params));
      watchdog('tes1', var_export($result, true));
      throw new \RestfulServerConfigurationException(format_string('Got error code @code from GitHub, with the following error message: @error', $params));
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
}
