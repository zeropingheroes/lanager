#!/usr/bin/env bash

set -e

exit

TEMP_DIR="/tmp"
STORAGE_BACKUP_FILENAME="lanager-storage.tar"
DB_BACKUP_FILENAME="lanager-database.sql"
ENV_BACKUP_FILENAME="lanager-environment.env"
ENV_FILENAME=".env"

echo "Getting image ID for local LANager image"
LANAGER_IMAGE_ID=$(docker images --filter="reference=lanager_app" --quiet)

echo "Getting image ID from backup file"
BACKUP_IMAGE_ID=

echo "Extracting backup file"
#TODO

echo "Restoring APP_KEY from $ENV_BACKUP_FILENAME to $ENV_FILENAME"
#TODO

echo "Restoring STEAM_API_KEY from $ENV_BACKUP_FILENAME to $ENV_FILENAME"
#TODO

echo "Restoring GOOGLE_API_KEY from $ENV_BACKUP_FILENAME to $ENV_FILENAME"
#TODO

echo "Loading database credentials from the .env file into environment variables"
source "$ENV_FILENAME"

echo "Restoring database data from $DB_BACKUP_FILE"
#TODO - how to do this while db container is not running?
docker run -i -e MYSQL_PWD=$DB_ROOT_PASSWORD --network lanager_app-network --rm mysql:8
   mysql -h$DB_HOST -uroot lanager < lanager.sql

echo "Restoring the storage/ directory into the lanager_laravel-storage volume"
#TODO - how to do this if volume doesn't exist yet? or already exists?

echo "Removing temporary directory $TEMP_DIR"
rm -rf "${TEMP_DIR:?}/$BACKUP_NAME"

echo "Successfully restored backup archive $BACKUP_FILE"
