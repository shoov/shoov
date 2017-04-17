#!/usr/bin/env bash

# if NOT git repo exit 1;

# if not git root, exit 1;


git clone https://github.com/amitaibu/gizra-behat .shoov-temp
cd .shoov-temp
cp .shoov.yml ../


tar zxf behat/behat-vendor.tar.gz
cp -R behat ../
cp ../behat/behat.local.yml.example ../behat/behat.local.yml
