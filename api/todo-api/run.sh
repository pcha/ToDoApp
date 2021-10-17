#!/bin/bash
echo "Cleaning cache" && bin/console cache:clear

echo "Creating app datatabase..." && pwd && ls && php bin/console doctrine:database:create --if-not-exists -n \
    && echo "Running migrations..." && php bin/console doctrine:migration:migrate -n \
    && echo "Creating test datatabase..." && php bin/console doctrine:database:create --if-not-exists -n --env=test \
    && echo "Runnig migrations in test database" && php bin/console doctrine:migration:migrate -n

echo "Starting Server" \
    && exec /usr/sbin/apache2ctl -DFOREGROUND
