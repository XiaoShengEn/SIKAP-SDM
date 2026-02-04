#!/bin/sh
set -e

# Ensure Vite dev server is not used in production containers.
if [ -f /var/www/html/public/hot ]; then
  rm -f /var/www/html/public/hot
fi

# Sync built assets into the public volume (if mounted).
# Do not fail container startup if the copy fails.
if [ -d /opt/public_build ]; then
  mkdir -p /var/www/html/public/build
  cp -a /opt/public_build/. /var/www/html/public/build/ || true
fi

# Ensure upload directories exist and are writable by PHP-FPM.
chown -R www-data:www-data /var/www/html/public || true
mkdir -p /var/www/html/public/uploads/profil /var/www/html/public/videos
chown -R www-data:www-data /var/www/html/public/uploads /var/www/html/public/videos
chmod -R 775 /var/www/html/public/uploads /var/www/html/public/videos

exec "$@"
