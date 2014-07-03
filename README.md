PommModule
==========

Pomm module for zf2

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