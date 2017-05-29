#! /bin/bash -

php -v | head -n1 | grep -q -vF 'PHP 5.2'
if [ $? -eq 0 ]; then
    composer test
else
    rm -rf src/ test/
    mv generated/* ./
    phpunit --bootstrap src/xKerman/Restricted/bootstrap.php
fi
