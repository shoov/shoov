#!/bin/bash

#########################################################################################
#
# Configuration used in the different scripts.
#
# Copy this file in the same directory, the filename of the copy should be "config.sh".
#
#########################################################################################


# The profile used to install the platform.
PROFILE_NAME="shoov"
# The human name of the install profile
PROFILE_TITLE="Shoov"


# Modify the URL below to match your local domain the site will be accessible on.
BASE_DOMAIN_URL="http://127.0.0.1:8080"


# Modify the login details below to be the desired
# login details for the Drupal Administrator account.
ADMIN_USERNAME="admin"
ADMIN_PASSWORD="admin"
ADMIN_EMAIL="admin@example.com"


# Modify the MySQL settings below so they will match your own.
MYSQL_USERNAME="root"
MYSQL_PASSWORD=""
MYSQL_HOSTNAME="127.0.0.1"
MYSQL_DB_NAME="drupal"



##
# External folders or files that need to be symlinked into the www folder
# AFTER the make files have been processed.
#
# The variable is an array, add each with an unique index number.
# Each line should contain the source path > target path.
# The target path needs to be relative to the www folder (Drupal root).
#
# Example:
#   SYMLINKS[0]="path/to/the/source/folder>subpath/of/the/www-folder"
##
# SYMLINKS[0]="/var/www/library/foldername>sites/all/library/foldername"
# SYMLINKS[1]="/var/www/shared/filename.php>sites/all/modules/filename.php"



##
# Post script functions.
#
# These functions are called when the corresponding script has finshed and
# before the final check of the platform (and optional auto login).
#
# Add commands that need to be run per script.
# The colors, as defined in the scripts/helper-colors.sh file, can be used to
# highlight echoed text.
#
# Following variables can be used (created depending on the script arguments):
# - $DEMO_CONTENT (0/1) : Should the demo content be loaded into the platform.
# - $AUTO_LOGIN (0/1)   : Will the script open a browser window and log in as an
#                         administrator.
# - $UNATTENDED (0/1)   : Is the script run unattended.
##

# Post install script.
function post_install {
  chmod 777 www/sites/default/settings.php

  # Github integration.
  echo "\$conf['shoov_github_client_id'] = '<your-client-id>';" >> www/sites/default/settings.php
  echo "\$conf['shoov_github_client_secret'] = '<your-client-secret>';"  >> www/sites/default/settings.php
  echo "\$conf['shoov_user_github_dummy'] = '<github-user-access-token>';"  >> www/sites/default/settings.php

  # Pusher integration.
  echo "\$conf['shoov_pusher_app_key'] = '<your-app-key>';"  >> www/sites/default/settings.php
  echo "\$conf['shoov_pusher_app_secret'] = '<your-app-secret>';"  >> www/sites/default/settings.php
  echo "\$conf['shoov_pusher_app_id'] = '<your-app-id>';"  >> www/sites/default/settings.php

  echo "\$conf['shoov_keen_project_id'] = '55f9371f46f9a75af42d9153';"  >> www/sites/default/settings.php
  echo "\$conf['shoov_keen_write_key'] = 'e7b29497f97627522d92d1a76bcd55eb29f46475568df17314fa4ba7e8f3725b2bd5f065c8e56491e1d26720a22e5baee701fecd9a0a8edffecf72336d532a325cc0100a52468e61e1f6a8444ee56e83792ea923104571e0a37f579f4a3da5eb11ec98edbe3658c7a23d2ccd037012e6';"  >> www/sites/default/settings.php
  echo "\$conf['shoov_keen_read_key'] = '386fd862896d584d06e39f1fd0e39a4f6347567e8069c10c476fcac87207a58a1d59e325465a9588b13cb3bbb9488fbbbcac05b76e4a791c6f370ca0e466c3dace170ae7172d2f677ce577a2caf9b8a5da9eab76e77040f154996fdc7c949c62e719d51e241ecdd5b40e862ed7da4c06';"  >> www/sites/default/settings.php
}

# Post upgrade script.
# function post_upgrade {}

# Post reset script.
# function post_reset {}
