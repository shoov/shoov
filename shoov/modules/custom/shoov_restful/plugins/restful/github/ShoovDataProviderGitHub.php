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
  protected $orgs = array();

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

    $repos = shoov_github_http_request('user/repos', $options);
    $user_repos = shoov_github_http_request('users/' . $wrapper->name->value() . '/repos', $options);

    $data = array_unique(array_merge($repos, $user_repos), SORT_REGULAR);

    $this->repos = $this->getKeyedById($data);

    // @todo: Make this configurable by the plugin.
    $this->syncLocalRepos();
    return $this->repos;
  }

  /**
   * Return the plugins.
   *
   * @return array
   */
  public function getOrgs() {
    if ($this->orgs) {
      return $this->orgs;
    }

    $wrapper = entity_metadata_wrapper('user', $this->getAccount());
    $access_token = $wrapper->field_github_access_token->value();

    $options = array(
      'headers' => array(
        'Authorization' => 'token ' . $access_token,
      ),
    );

    $orgs = shoov_github_http_request('user/orgs', $options);
    // Add user to organization list as user can be the owner of the repository
    // like an organization.
    $user = shoov_github_http_request('user', $options);

    $data = array_unique(array_merge($orgs, array($user)), SORT_REGULAR);

    $this->orgs = $this->getKeyedById($data);

    // @todo: Make this configurable by the plugin.
    return $this->orgs;
  }

  /**
   * Return the plugins list, keyed by ID.
   *
   * We also take advantage of having the data, in order to update the plugin name
   * of local plugin.
   *
   * @param array $plugins
   *   The plugins list.
   *
   * @return array
   *   Array keyed by the plugin ID.
   */
  protected function getKeyedById(array $plugins) {
    $return = array();
    foreach ($plugins as $plugin) {
      $return[$plugin['id']] = $plugin;
    }

    return $return;
  }

  /**
   * @todo: Update the repository title by the GitHub repo ID.
   */
  protected function syncLocalRepos() {
    // Get all the local repos by the GitHub repo ID.
    $ids = array_keys($this->repos);

    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'repository')
      ->propertyCondition('status', NODE_PUBLISHED)
      ->fieldCondition('field_github_id', 'value', $ids, 'IN')
      ->execute();

    if (empty($result['node'])) {
      // No matching local repos.
      return;
    }

    $repo_ids = array_keys($result['node']);
    $repo_nodes = array();

    foreach(node_load_multiple($repo_ids) as $repo) {
      // Key array by repo node ID.
      $repo_nodes[$repo->nid] = $repo;
    }

    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'ci_build')
      ->propertyCondition('status', NODE_PUBLISHED)
      ->fieldCondition('og_repo', 'target_id', $repo_ids, 'IN')
      ->execute();

    if (!empty($result['node'])) {
      foreach (node_load_multiple(array_keys($result['node'])) as $build) {
        $build_wrapper = entity_metadata_wrapper('node', $build);
        $repo_id = $build_wrapper->og_repo->value(array('identifier' => TRUE));

        // Add the build info.
        // @todo: use the CI-build RESTful resource.
        $repo_nodes[$repo_id]->_ci_build = array(
          'id' => $build->nid,
          'branch' => $build_wrapper->field_git_branch->value(),
          'enabled' => $build_wrapper->field_ci_build_enabled->value(),
        );

      }

    }

    foreach ($repo_nodes as $node) {
      $wrapper = entity_metadata_wrapper('node', $node);
      $github_id = $wrapper->field_github_id->value();

      if (empty($this->repos[$github_id])) {
        // @todo: Delete the repo and content?
        // Repo does no longer exist.
        continue;
      }


      $repo = &$this->repos[$github_id];
      $repo['shoov_id'] = $node->nid;

      // Get the build info.
      $repo['shoov_build'] = !empty($node->_ci_build) ? $node->_ci_build : NULL;
    }
  }

  /**
   * Gets the plugins filtered and sorted by the request.
   *
   * @param array $plugins
   *  Array of plugins.
   *
   * @return array
   *   Array of plugins.
   */
  public function getSortedAndFiltered($plugins) {
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
    if ($this->plugin['resource'] == 'github_orgs') {
      return count($this->getSortedAndFiltered($this->getOrgs()));
    }
    else {
      return count($this->getSortedAndFiltered($this->getRepos()));
    }
  }

  public function index() {
    $return = array();

    if ($this->plugin['resource'] == 'github_orgs') {
      foreach (array_keys($this->getSortedAndFiltered($this->getOrgs())) as $plugin_name) {
        $return[] = $this->view($plugin_name);
      }
    }
    else {
      foreach (array_keys($this->getSortedAndFiltered($this->getRepos())) as $plugin_name) {
        $return[] = $this->view($plugin_name);
      }
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

    $item = $this->plugin['resource'] == 'github_orgs' ? $this->orgs[$id] : $this->repos[$id];

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
        $value = static::executeCallback($info['callback'], array($item));
      }
      // Map row names to public properties.
      elseif ($info['property']) {
        $value = !empty($item[$info['property']]) ? $item[$info['property']] : NULL;
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

  protected function organizationProcess($value) {
    return $value['login'];
  }
}

