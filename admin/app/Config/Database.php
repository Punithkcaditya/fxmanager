<?php

namespace Config;

use CodeIgniter\Database\Config;


use Config\Services;



// Get the database name from the session

/**
 * Database Configuration
 */
class Database extends Config
{
 
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     *
     * @var string
     */
    public $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     *
     * @var string
     */
    public $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array
     */
    public $default = [
        'DSN'      => '',
        'hostname' => 'localhost:3308',
        'username' => 'root',
        'password' => '',
        'database' => '',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];


  

    public $second_db = [
        'dsn'      => '',
        'hostname' => 'godigistores.cmnfaak6okbi.ap-south-1.rds.amazonaws.com:3306',
        'username' => 'retail',
        'password' => 'q1w2e3r4t5Y^U&I*O(P)',
        'database' => 'devisen',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    // public $second_db = [
    //     'dsn'	=> '',
    //     'hostname' => 'localhost:3308',
    //     'username' => 'root',
    //     'password' => '',
    //     'database' => 'devisen',
    //     'dbdriver' => 'MySQLi',
    //     'dbprefix' => '',
    //     'pconnect' => FALSE,
    //     'db_debug' => (ENVIRONMENT !== 'production'),
    //     'cache_on' => FALSE,
    //     'cachedir' => '',
    //     'char_set' => 'utf8',
    //     'dbcollat' => 'utf8_general_ci',
    //     'swap_pre' => '',
    //     'encrypt' => FALSE,
    //     'compress' => FALSE,
    //     'stricton' => FALSE,
    //     'failover' => array(),
    //     'save_queries' => TRUE
    // ];



    // public $default = [
    //     'DSN'      => '',
    //     'hostname' => 'localhost:3308',
    //     'username' => 'root',
    //     'password' => '',
    //     'database' => DB_NAME,
    //     'DBDriver' => 'MySQLi',
    //     'DBPrefix' => '',
    //     'pConnect' => false,
    //     'DBDebug'  => (ENVIRONMENT !== 'production'),
    //     'charset'  => 'utf8',
    //     'DBCollat' => 'utf8_general_ci',
    //     'swapPre'  => '',
    //     'encrypt'  => false,
    //     'compress' => false,
    //     'strictOn' => false,
    //     'failover' => [],
    //     'port'     => 3306,
    // ];

    /**
     * This database connection is used when
     * running PHPUnit database tests.
     *
     * @var array
     */
    public $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => (ENVIRONMENT !== 'production'),
        'charset'     => 'utf8',
        'DBCollat'    => 'utf8_general_ci',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
    ];

    public function __construct()
    {
        parent::__construct();

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
