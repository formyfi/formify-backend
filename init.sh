#!/bin/bash

set -e

# Wait for MySQL to start
until mysql -h mysql -u root -p${MYSQL_ROOT_PASSWORD} -e ";" ; do
    echo "MySQL is not yet available - sleeping"
    sleep 1
done

# Import SQL file
mysql -h mysql -u root -p${MYSQL_ROOT_PASSWORD} < ./var/www/html/docker-entrypoint-initdb.d/database.sql