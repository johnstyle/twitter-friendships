#!/usr/bin/env bash

ABS="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

php "${ABS}/bin/sync"

if [ "${1}" = '--unfollow' ]; then

    php "${ABS}/bin/unfollow"
fi

php "${ABS}/bin/follow"
php "${ABS}/bin/search"

