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
        // Get or create a new token.
        \RestfulInterface::POST => 'getUser',
      ),
    );
  }

  protected function getUser() {
    $request = $this->getRequest();
    if (empty($request['code'])) {
      throw new \RestfulUnauthorizedException('code property is missing.');
    }

    $request = array(
      'method' => 'POST',
      'data' => http_build_query(array(
        'client_id' => variable_get('shoov_github_client_id'),
        'client_secret' => variable_get('shoov_github_client_secret'),
        'code' => $request['code'],
      )),
    );

    $result = drupal_http_request('https://github.com/login/oauth/access_token', $request);

    // Result format is:
    // 'access_token=someTokenValue&scope=&token_type=bearer';

    $access_token = $result->data;

    $access_token = explode('&', $result->data);
    $access_token = explode('=', $access_token[0]);
    $access_token = $access_token[1];

    $request = array(
      // 'data' => $access_token,
      'headers' => array(
        'Authorization' => 'token ' . $access_token,
      ),
    );

    $result = drupal_http_request('https://api.github.com/user', $request);

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

  protected function createUser($data, $request) {
    //set up the user fields
    $fields = array(
      'name' => $data['login'],
      // @todo: Get email from GitHub.
      'mail' => $this->getEmail($request),
      'pass' => user_password(8),
      'status' => TRUE,
      'roles' => array(
        DRUPAL_AUTHENTICATED_RID => 'authenticated user',
      ),
    );

    //the first parameter is left blank so a new user is created
    $account = user_save('', $fields);
    return $account;
  }

  protected function getEmail($request) {
    $result = drupal_http_request('https://api.github.com/user/emails', $request);
    foreach (drupal_json_decode($result->data) as $row) {
      if (empty($row['primary'])) {
        // Not the primary email.
        continue;
      }

      return $row['email'];
    }
  }
}
