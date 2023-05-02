#!/usr/bin/env bash

set -eux

prepare_packages() {
  echo "Composer: Installing missing packages..."

  composer install --prefer-dist --no-progress --no-interaction --no-scripts

  echo "Composer: Packages installed"
}

prepare_database() {
  echo "DB: Waiting for db to be ready..."

  until bin/console dbal:run-sql "SELECT 1"; do
    sleep 1
  done

  echo "DB: Is ready"
}

if [[ -f "composer.json" ]]; then
  prepare_packages ;
#  prepare_database ;
fi

echo "All done!"

exec supervisord
