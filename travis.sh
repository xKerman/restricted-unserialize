#! /bin/bash -

php -v | head -n1 | grep -q -vF 'PHP 5.2'
if [ $? -eq 0 ]; then
    composer test
else
    phpunit --bootstrap generated/src/xKerman/Restricted/bootstrap.php --configuration phpunit.php52.xml
fi
