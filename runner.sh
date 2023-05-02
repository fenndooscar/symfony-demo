#!/usr/bin/env sh

set -eu

prepare_env() {
    set -o allexport

    if [ ! -f "./docker-compose.env" ]; then
      echo "docker-compose.env does not exists"
      echo "aborting"

      exit 1
    fi

    . ./docker-compose.env

    [ -f "./docker-compose.env.local" ] && . ./docker-compose.env.local

    set +o allexport
}

pull_images() {
    docker-compose pull
}

start_services() {
    docker-compose up --build
}

stop_services() {
    docker-compose stop
}

down_services() {
    docker-compose down -v
}

run_php() {
    docker-compose exec app "$@"
}

run_composer() {
    run_php composer "$@"
}

run_symfony() {
    run_php bin/console "$@"
}

run_phpstan() {
  run_php php /srv/app/vendor/bin/phpstan analyse src --level=max
}

run_cs_fix() {
  run_php php /srv/app/vendor/bin/php-cs-fixer fix
}

run_phpunit() {
    run_php bin/phpunit "$@"
}

postgres_shell() {
    docker exec -ti postgres symfony-demo -u"${POSTGRES_USER}" -p"${POSTGRES_PASSWORD}" "${POSTGRES_DATABASE}"
}

reset_db() {
    run_symfony doctrine:database:drop --force && \
    run_symfony doctrine:database:create && \
    run_symfony doctrine:migrations:migrate && \
    run_symfony doctrine:fixtures:load
}

# shellcheck disable=SC2124
command_full="$@"
command_name="$1"
command_args=$(echo "${command_full#"$command_name"}" | sed 's/^[[:space:]]*//g')

echo "command full:   runner.sh ${command_full}"
echo "command name:   ${command_name}"
echo "command args:   ${command_args}"
echo "command output: "
echo ""

(
prepare_env

case ${command_name} in
    docker-compose)
        docker-compose "${command_args}"
        ;;
    start)
        pull_images
        start_services
        ;;
    stop)
        stop_services
        ;;
    down)
        down_services
        ;;
    php)
        run_php "${command_args}"
        ;;
    composer)
        run_composer "${command_args}"
        ;;
    symfony)
        run_symfony "${command_args}"
        ;;
    phpunit)
        run_phpunit "${command_args}"
        ;;
    postgres)
        postgres_shell
        ;;
    cs:analyse)
        run_phpstan
        ;;
    cs:fix)
        run_cs_fix
        ;;
    db:reset)
      reset_db
      ;;
    *)
        echo "${command_name} is unknown"
        exit 1
        ;;
esac
)
