#!/bin/bash

ROOT=$(pwd)
cd $ROOT
cd $ROOT/www

echo "Importing database"
drush sql-drop -y
pv ~/Downloads/shoov_live_2015-11-28T10-00-00_UTC_database.sql.gz | gunzip | drush sql-cli

echo "Sanitize mails and github tokens"
drush sql-query 'UPDATE users SET mail = CONCAT(mail, ".test");'
drush sql-query 'UPDATE field_data_field_github_access_token SET field_github_access_token_value = "helena" WHERE entity_id IN (SELECT uid FROM users WHERE name = "HelenaEksler");'

echo "Disable logs module"
drush dis logs_http -ygitx

