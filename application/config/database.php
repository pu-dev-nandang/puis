<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$active_group = 'default';
$active_record = TRUE;

$ServerName = $_SERVER['SERVER_NAME'];
switch ($ServerName) {
    case 'localhost':
        $db['default']['hostname'] = 'localhost';
        $db['default']['username'] = 'root';
        $db['default']['password'] = '';
        $db['default']['database'] = 'db_academic';
        $db['default']['dbdriver'] = 'mysqli';// support with MYSQl,POSTGRE SQL, ORACLE,SQL SERVER
        $db['default']['dbprefix'] = '';
        $db['default']['pconnect'] = TRUE;
        $db['default']['db_debug'] = TRUE;
        $db['default']['cache_on'] = FALSE;
        $db['default']['cachedir'] = '';
        $db['default']['char_set'] = 'utf8';
        $db['default']['dbcollat'] = 'utf8_general_ci';
        $db['default']['swap_pre'] = '';
        $db['default']['autoinit'] = TRUE;
        $db['default']['stricton'] = FALSE;

        $db['server'] = array(
            'dsn'   => '',
            'hostname' => '10.1.30.88',
            'username' => 'it',
            'password' => 'itypap888',
            'database' => 'siak4',
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );

        $db['server22'] = array(
            'dsn'   => '',
            'hostname' => '10.1.30.22',
            'username' => 'root',
            'password' => 'itypap888',
            'database' => 'library',
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );
        break;
    case 'pcam.podomorouniversity.ac.id':
        $db['default']['hostname'] = '10.1.30.18';
        $db['default']['username'] = 'db_itpu';
        $db['default']['password'] = 'Uap)(*&^%';
        $db['default']['database'] = 'db_academic';
        $db['default']['dbdriver'] = 'mysqli';// support with MYSQl,POSTGRE SQL, ORACLE,SQL SERVER
        $db['default']['dbprefix'] = '';
        $db['default']['pconnect'] = TRUE;
        $db['default']['db_debug'] = TRUE;
        $db['default']['cache_on'] = FALSE;
        $db['default']['cachedir'] = '';
        $db['default']['char_set'] = 'utf8';
        $db['default']['dbcollat'] = 'utf8_general_ci';
        $db['default']['swap_pre'] = '';
        $db['default']['autoinit'] = TRUE;
        $db['default']['stricton'] = FALSE;
        break;
    case 'demo.pcam.podomorouniversity.ac.id':
        $db['default']['hostname'] = 'localhost';
        $db['default']['username'] = 'root';
        $db['default']['password'] = 'Uap)(*&^%';
        $db['default']['database'] = 'db_academic';
        $db['default']['dbdriver'] = 'mysqli';// support with MYSQl,POSTGRE SQL, ORACLE,SQL SERVER
        $db['default']['dbprefix'] = '';
        $db['default']['pconnect'] = TRUE;
        $db['default']['db_debug'] = TRUE;
        $db['default']['cache_on'] = FALSE;
        $db['default']['cachedir'] = '';
        $db['default']['char_set'] = 'utf8';
        $db['default']['dbcollat'] = 'utf8_general_ci';
        $db['default']['swap_pre'] = '';
        $db['default']['autoinit'] = TRUE;
        $db['default']['stricton'] = FALSE;
        break;    
    default:
        # code...
        break;
}

/* End of file database.php */
/* Location: ./application/config/database.php */