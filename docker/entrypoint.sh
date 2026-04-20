#!/usr/bin/env sh

# Exit immediately on first error
set -e

# Treat unset variables as an error
set -u

# Create the storage directories Laravel & LANager require
mkdir -p \
    /app/storage/app/public \
    /app/storage/app/private \
    /app/storage/app/steam-api \
    /app/storage/framework/cache \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs

# If the storage directory is not writeable, exit
if [ ! -w /app/storage ]; then
    echo "Error: /app/storage is not writable by the container user." >&2
    echo "Container is running as UID $(id -u) and GID $(id -g)." >&2
    echo "Ensure the UID/GID has write permission to /app/storage." >&2
    exit 1
fi

# Pass commands to the shell
exec "$@"
