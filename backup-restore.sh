#!/usr/bin/env bash

set -e

if [ "$#" -ne 1 ]
then
    echo "Usage: ./backup-restore.sh <file>"
    echo ""
    echo "Restore a LANager backup file"
    exit 1
fi

if [[ ! -f $1 ]]; then
    echo "Error: File not found: $1"
    exit 1
fi

CONTAINER_NAME="app"

if [ "$( docker container inspect -f '{{.State.Status}}' $CONTAINER_NAME )" != "running" ]; then
    echo "Error: Container \"$CONTAINER_NAME\" is not running"
    exit 1;
fi

echo "Getting image ID for local LANager image"
LOCAL_IMAGE_ID=$(docker images --filter="reference=lanager_app" --quiet)

if [[ -z "$LOCAL_IMAGE_ID" ]]; then
    echo "Error: could not find image ID for local LANager image"
    exit 1
fi

echo "Getting image ID from backup filename"

if [[ $1 =~ ([0-9a-fA-F]{12}) ]]; then
  BACKUP_IMAGE_ID="${BASH_REMATCH[1]}"
else
  echo "Error: could not find image ID in backup filename"
  exit 1
fi

echo "Checking LANager backup image ID matches local LANager image ID"
if [[ "$BACKUP_IMAGE_ID" != "$LOCAL_IMAGE_ID" ]]; then
    echo "Error: Local image ID ($LOCAL_IMAGE_ID) is different to backup image ID ($BACKUP_IMAGE_ID)"
    exit 1
else
    echo "Image IDs match: $BACKUP_IMAGE_ID"
fi

exit

TEMP_DIR="/tmp"
STORAGE_BACKUP_FILENAME="lanager-storage.tar"
DB_BACKUP_FILENAME="lanager-database.sql"
ENV_BACKUP_FILENAME="lanager-environment.env"
ENV_FILENAME=".env"

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
