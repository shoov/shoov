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

  echo "\$conf['shoov_keen_project_id'] = '55fa6e4d96773d25ec4d23b3';"  >> www/sites/default/settings.php
  echo "\$conf['shoov_keen_write_key'] = '30ac16f9ef819b7704063365040ef7edee6697c14ce639d46f4b39cd329062880dd5fc78e8fbdfb7422ecd2dca86ada8c3f971e074a4a19ac6f35df4d5494a1b97aef4f20a21af31af8ea7298de0522b86f0119df42dbc933632093864330c59569ee1aeeda5a3e98be00773932eaf9c';"  >> www/sites/default/settings.php
  echo "\$conf['shoov_keen_read_key'] = 'a9ce199cc111c2930cad863945a76250a57feef7a87f34919d8ad8a508d8e254573c3d2c198048aab0ec7ceb7fa9a24940b6ae48f2cba090b1f3f3d1a1f7cdd4e526a7abe690a7d5d20e120592773c37855eab4877550f778e9141d4c7eed168ab6df62205ac6c0daebbb985d4f4a348';"  >> www/sites/default/settings.php
}

# Post upgrade script.
# function post_upgrade {}

# Post reset script.
# function post_reset {}
