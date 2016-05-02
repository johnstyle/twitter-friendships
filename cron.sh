#!/usr/bin/env bash

php bin/sync

if [ "${1}" = '--unfollow' ]; then

    php bin/unfollow
fi

php bin/follow
php bin/search
