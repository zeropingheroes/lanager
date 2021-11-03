#!/usr/bin/env bash

set -e

CONTAINER_NAME="app"

if [ "$( docker container inspect -f '{{.State.Status}}' $CONTAINER_NAME )" != "running" ]; then
    echo "Error: Container \"$CONTAINER_NAME\" is not running"
    exit 1;
fi

echo "Getting image ID for local LANager image"
LANAGER_IMAGE_ID=$(docker images --filter="reference=lanager_app" --quiet)

DATE=$(date +"%Y-%m-%d")
BACKUP_NAME="lanager-backup-$DATE-build-$LANAGER_IMAGE_ID"
BACKUP_FILE="$BACKUP_NAME.tar.gz"
TEMP_DIR="/tmp"
DB_BACKUP_FILE="$TEMP_DIR/$BACKUP_NAME/lanager-database.sql"
STORAGE_BACKUP_FILE="lanager-storage.tar"
ENV_BACKUP_FILE="$TEMP_DIR/$BACKUP_NAME/lanager-environment.env"

echo "Creating temporary directory for backup data: $TEMP_DIR/$BACKUP_NAME"
mkdir -p "$TEMP_DIR/$BACKUP_NAME"

echo "Loading database credentials from the .env file into environment variables"
source .env

echo "Dumping database data into $DB_BACKUP_FILE"
docker run -it -e MYSQL_PWD="$DB_ROOT_PASSWORD" --network lanager_app-network --rm mysql:8 \
       mysqldump -h"$DB_HOST" -uroot --add-drop-database --databases "$DB_DATABASE" > "$DB_BACKUP_FILE"

echo "Backing up the storage/ directory stored in the lanager_laravel-storage volume into $TEMP_DIR/$BACKUP_NAME/$STORAGE_BACKUP_FILE"
docker run --rm --volumes-from app -v "$TEMP_DIR":/backup php:7.4-fpm tar cf "/backup/$BACKUP_NAME/$STORAGE_BACKUP_FILE" \
   /var/www/storage

echo "Backing up the .env file into $ENV_BACKUP_FILE"
cp .env "$ENV_BACKUP_FILE"

echo "Compressing all backup files into $BACKUP_NAME.tar.gz"
tar czf "$BACKUP_FILE" -C "$TEMP_DIR" "$BACKUP_NAME"

echo "Removing temporary directory"
rm -rf "${TEMP_DIR:?}/$BACKUP_NAME"

echo "Successfully created backup archive"
