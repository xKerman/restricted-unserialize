#! /bin/bash -

php -v | head -n1 | grep -q -vF 'PHP 5.2'
if [ $? -eq 0 ]; then
    composer test
else
    rm -rf src/ test/
    mv generated/* ./
    pecl install xdebug-2.2.7
    sed -i '/zend_extension_ts="xdebug.so"/d' ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
    sed -i 's/zend_extension/zend_extension_ts/g' ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini
    phpunit --bootstrap src/xKerman/Restricted/bootstrap.php
fi