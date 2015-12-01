#!/bin/bash

ROOT=$(pwd)
ZIPPATH="~/Downloads/shoov_database.sql.gz"
USERNAME="GithubUsername"
TOKEN="ReplaceWithToken"

cd $ROOT
cd $ROOT/www

echo "Drop Database"
drush sql-drop -y
echo "Unzip and import database"
gunzip < $ZIPPATH | drush sql-cli

# This can be used instead of the previous command if you have pv installed.
# If use pv - you'll see the status bar.
#pv $ZIPPATH | gunzip | drush sql-cli

echo "Sanitize mails and github tokens"
drush sql-query 'UPDATE users SET mail = CONCAT(mail, ".test");'
drush sql-query "UPDATE field_data_field_github_access_token SET field_github_access_token_value = '${TOKEN}' WHERE entity_id NOT IN (SELECT uid FROM users WHERE name = '${USERNAME}');"

echo "Disable logs module"
drush dis logs_http -y

