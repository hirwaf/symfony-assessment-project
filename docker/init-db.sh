#!/bin/sh

echo "Starting init-db.sh script"

echo "CREATE DATABASE ${POSTGRES_DATABASE};" | psql -A -U "$POSTGRES_USER"

echo "Finished init-db.sh script"