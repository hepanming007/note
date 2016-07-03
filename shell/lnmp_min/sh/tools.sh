#!/bin/bash
php_redis_admin(){
    git clone https://github.com/ErikDubbelboer/phpRedisAdmin.git
    cd phpRedisAdmin
    git clone https://github.com/nrk/predis.git vendor
}