<?php

// Show inactive clients (false by default)
$config['show_inactive_clients'] = true;

// Hide empty pools (displayed by default)
$config['hide_empty_pools'] = false;

// Jobs per page (Jobs report page)
$config['jobs_per_page'] = 25;

// Translations
$config['language'] = 'en_US';

// en_US -> English - maintened by Davide Franco (bacula-dev@dflc.ch)
// es_ES -> Spanish - Mantained by Juan Luis Franc�s Jim�nez
// it_IT -> Italian - Mantained by Gian Domenico Messina (gianni.messina AT c-ict.it)
// fr_FR -> French - Mantained by Morgan LEFIEUX (comete AT daknet.org)
// de_DE -> German - Mantained by Florian Heigl
// sv_SV -> Swedish - Maintened by Daniel Nylander (po@danielnylander.se)
// pt_BR -> Portuguese Brazil - Last updated by J. Ritter (condector@gmail.com) 
// nl_NL -> Dutch - last updated by Dion van Adrichem

// Database connection parameters
// Copy/paste and adjust parameters according to your configuration

// For Unix socket connection, use parameters decribed below
// MySQL: use localhost for $config[0]['host']
// postGresql: do not define $config[0]['host']

// MySQL bacula catalog
$config[0]['label'] = 'Backup Server';
$config[0]['host'] = '{{ bacula_web_db_host }}';
$config[0]['login'] = '{{ bacula_web_db_user }}';
$config[0]['password'] = '{{ bacula_web_db_password }}';
$config[0]['db_name'] = '{{ bacula_web_db_name }}';
$config[0]['db_type'] = 'mysql';
$config[0]['db_port'] = '{{ bacula_web_db_port }}';

// PostgreSQL bacula catalog
// $config[0]['label'] = 'Prod Server';
// $config[0]['host'] = 'db-server.domain.com';
// $config[0]['login'] = 'bacula';
// $config[0]['password'] = 'otherstrongpassword';
// $config[0]['db_name'] = 'bacula';
// $config[0]['db_type'] = 'pgsql';
// $config[0]['db_port'] = '5432'; 

// SQLite bacula catalog
// $config[0]['label'] = 'Dev backup server';
// $config[0]['db_type'] = 'sqlite';
// $config[0]['db_name'] = '/path/to/database/db.sdb';
// Copy the section below only if you have at least two Bacula catalog
// Don't forget to modify options such as label, host, login, password, etc.

// 2nd bacula catalog (MySQL)
// $config[1]['label'] = 'Dev backup server';
// $config[1]['host'] = 'mysql-server.domain.net';
// $config[1]['login'] = 'bacula';
// $config[1]['password'] = 'verystrongpassword';
// $config[1]['db_name'] = 'bacula';
// $config[1]['db_type'] = 'mysql';
// $config[1]['db_port'] = '3306';
?>