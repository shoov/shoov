#!/usr/bin/env bash

# Execute: ./pantheon-site/upgrade_scripts/321.sh dev
# Execute: ./pantheon-site/upgrade_scripts/321.sh dev 10

if [ $# -eq 0 ]
  then
    echo "You must pass the Pantheon enviorement (dev, test, live)"
    exit 1
fi

ENV="$1"

if [ $# -eq 2 ]
  then
    NID="--nid=$2"
  else
    NID=""
fi

echo "Processing @pantheon.shoov.$ENV"

drush @pantheon.shoov.$ENV scr shoov_upgrades/321-disable_ci_builds.php $NID -y --strict=0
