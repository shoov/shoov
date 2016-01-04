#!/usr/bin/env bash

# Execute: ./pantheon-site/upgrade_scripts/270-JS-LM-permissions.sh dev

if [ $# -eq 0 ]
  then
    echo "You must pass the Pantheon enviorement (dev, test, live)"
    exit 1
fi

ENV="$1"

echo "Processing @pantheon.shoov.$ENV"

drush @pantheon.shoov.$ENV vset og_node_access_strict 0 -y --strict=0
