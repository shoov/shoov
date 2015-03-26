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
        \RestfulInterface::GET => 'getOrCreateToken',
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
        'code' => $_GET['code'],
      )),
    );

    $result = drupal_http_request('https://github.com/login/oauth/access_token', $request);

    // Get the username from Github and compare with ours.
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'user')
      ->propertyCondition('name', $name)
      ->range(0, 1)
      ->execute();

    if (empty($result['user'])) {
      // Create a new user.
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
}
