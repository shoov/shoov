#!/bin/bash

################################################################################
#
# This script will sync back to the original generator.
#
#
################################################################################


GENERATOR_FOLDER="/var/www/generator-hedley"

# Client
rsync -avz --exclude-from 'sync_hedley_exclude.txt' ./client $GENERATOR_FOLDER/app/templates

# Behat
rsync -avz --exclude-from 'sync_hedley_exclude.txt' ./behat $GENERATOR_FOLDER/app/templates

# PhantomCSS
rsync -avz --exclude-from 'sync_hedley_exclude.txt' ./phantomcss $GENERATOR_FOLDER/app/templates

# Drupal - we make sure to hardcode the copy to "shoov".
rsync -avz --exclude-from 'sync_hedley_exclude.txt' ./shoov $GENERATOR_FOLDER/app/templates



