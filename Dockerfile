############################################################
# Dockerfile to images container with PHP/Behat/Selenium tests
# Based on million12/php-testing
############################################################

# Base image
FROM million12/php-testing

# Create the default data directory
RUN mkdir -p /behat_test

# Add dir with tests
ADD features /behat_test/features

# Get needed dependencies
ADD composer.json /behat_test/composer.json
RUN cd /behat_test && composer install

# Add behat config file
ADD behat.yml /behat_test/behat.yml

# Install file manager
RUN yum -y install mc

# Fix "Unable to connect to host 127.0.0.1 on port 7055" error
RUN dbus-uuidgen > /var/lib/dbus/machine-id

#working dir
WORKDIR /behat_test

#lauch tests at docker run
CMD bin/behat