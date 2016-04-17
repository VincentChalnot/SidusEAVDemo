Sidus / EAV Demo
================

See [Sidus/EAVModelBundle documentation](https://github.com/VincentChalnot/SidusEAVModelBundle) for further information

## Dev Installation

    $ composer install
    $ app/console doctrine:database:create
    $ app/console doctrine:schema:create

## Server installation

PHP stack
    $ apt-get install php5-intl php5-mysql php5-curl php5-gd php5-apcu

Third-party requirements:
    $ apt-get install npm
    $ npm install -g uglifyjs
    $ npm install -g uglifycss
