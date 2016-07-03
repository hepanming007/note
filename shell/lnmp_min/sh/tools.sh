#!/bin/bash

install_composer(){
     curl -s http://getcomposer.org/installer|/usr/local/php/bin/php
     mv composer.phar /usr/local/bin/composer
}

php_redis_admin(){
    #http://www.test.com/redis/
    git clone https://github.com/ErikDubbelboer/phpRedisAdmin.git
    cd phpRedisAdmin
    composer install
    # /usr/local/php/bin/php composer.phar install
}
