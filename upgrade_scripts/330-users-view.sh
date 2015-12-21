#!/usr/bin/env bash

# Execute: ./pantheon-site/upgrade_scripts/330-users-view.sh dev

if [ $# -eq 0 ]
  then
    echo "You must pass the Pantheon enviorement (dev, test, live)"
    exit 1
fi

ENV="$1"

echo "Processing @pantheon.shoov.$ENV"

drush @pantheon.shoov.$ENV en views_data_export -y --strict=0
