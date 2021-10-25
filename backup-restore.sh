#!/usr/bin/env bash

set -e

APP_CONTAINER_NAME="app"
DB_CONTAINER_NAME="db"
STORAGE_VOLUME_NAME="lanager_laravel-storage"

TEMP_DIR="/tmp"
BACKUP_FOLDER="$TEMP_DIR/${1%%.*}"
STORAGE_BACKUP_FILENAME="lanager-storage.tar"
DB_BACKUP_FILENAME="lanager-database.sql"
ENV_BACKUP_FILENAME="lanager-environment.env"
LOCAL_ENV_FILENAME=".env"

if [ "$#" -ne 1 ]; then
    echo "Usage: ./backup-restore.sh <file>"
    echo ""
    echo "Restore a LANager backup file"
    exit 1
fi

if [[ ! -f $1 ]]; then
    echo "Error: File not found: $1"
    exit 1
fi

echo "WARNING: This will overwrite any local LANager data"

read -p "Do you wish to continue? " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    exit
fi

if [ "$( docker container inspect -f '{{.State.Status}}' $APP_CONTAINER_NAME )" != "running" ]; then
    echo "Error: Container \"$APP_CONTAINER_NAME\" is not running"
    exit 1;
fi

if [ "$( docker container inspect -f '{{.State.Status}}' $DB_CONTAINER_NAME )" != "running" ]; then
    echo "Error: Container \"$DB_CONTAINER_NAME\" is not running"
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

echo "Extracting backup file into $BACKUP_FOLDER"
tar xzf "$1" -C "$TEMP_DIR"

read -p "Would you like to restore the environment settings (.env) file? " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    mv "$BACKUP_FOLDER/$ENV_BACKUP_FILENAME" ".env"
fi

echo "Loading database credentials from the .env file into environment variables"
source "$LOCAL_ENV_FILENAME"

echo "Restoring database data from $DB_BACKUP_FILENAME"
docker run -i -e "MYSQL_PWD=$DB_ROOT_PASSWORD" --network lanager_app-network --rm mysql:8 \
   mysql "-h$DB_HOST" -uroot "$DB_DATABASE" < "$BACKUP_FOLDER/$DB_BACKUP_FILENAME"

echo "Destroying all data in the $STORAGE_VOLUME_NAME volume"
docker run --rm --volumes-from app -v "$BACKUP_FOLDER":/restore php:7.4-fpm rm -rf /var/www/storage/*

echo "Restoring files from the storage directory into the $STORAGE_VOLUME_NAME volume"
docker run --rm --volumes-from app -v "$BACKUP_FOLDER":/restore php:7.4-fpm tar xf "/restore/$STORAGE_BACKUP_FILENAME" \
   -C /

echo "Removing temporary directory"
rm -rf "${BACKUP_FOLDER:?}"

echo "Successfully restored backup archive"
