PommModule
==========

Pomm module for zf2

Installation
------------

This package can be installed via composer

   https://packagist.org/packages/jitb/pomm-module

Configuration
-------------

Just add it to your application configuration file

return array(
    'pomm' => array(
        'databases' => array(
            'con1' => array (
                'dsn'  => 'pgsql://postgres:postgres@127.0.0.1/myschema',
                'name' => 'con1',
            ),
        ),
    ),
);

TODO
----

Add configuration for auth adapter
Add paginator
