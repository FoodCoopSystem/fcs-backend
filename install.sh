#!/bin/bash

# This script install application and fills it with fixtures. 
# It was tested on Linux systems, but should work also on MacOS.


# Simple trick for changing colors. It's taken from
# http://stackoverflow.com/questions/5947742/how-to-change-the-output-color-of-echo-in-linux
SWITCH="\033["
NORMAL="${SWITCH}0m"
RED="${SWITCH}1;31m"


if !(hash php &> /dev/null); then
    echo -en $RED;
    echo "PHP CLI in a version >= 5.5 is required for installation".
    echo -en $NORMAL;
    exit 1;
fi;


if !(hash curl &> /dev/null); then
    echo "Curl is required for installation".
    exit 2;
fi;


# Install composer
curl -sS https://getcomposer.org/installer | php

# Install all dependencies
php composer.phar install

# Create database
 php app/console doctrine:database:drop --force   
 php app/console doctrine:database:create  

# Prepare testing configuration
cp behat.yml.dist behat.yml
cp phpspec.yml.dist phpspec.yml

php app/check.php

php ./app/console doctrine:schema:update --force


# Now set valid permissions


