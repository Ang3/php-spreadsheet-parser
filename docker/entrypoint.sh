#!/bin/sh
set -e

git config --global --add safe.directory /app

if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
  composer install --prefer-dist --no-progress --no-interaction
else
  composer install
fi

echo 'PHP app ready!'

exec "$@"