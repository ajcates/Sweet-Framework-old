<?
$config['timeout'] = 31536000; //1 year
$config['hashFunction'] = 'sha512';
//To get my cookie secert I use the 63 random alpha-numeric characters (a-z, A-Z, 0-9): from https://www.grc.com/passwords.htm then I just pound on my keyboard some to add some more random salt.
$config['cookieSecret'] = 'h07rsouY43hSNpNAVvcEKDrXkzs4rasdfasdfkOL9iUe3Vx5RjTfrthw49BC6xeGNvw2nI55z1RH';
$config['cookieName'] = 'crad-token';
$config['use'] = array($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
$config['sslCookies'] = false;
$config['tableName'] = 'Club_RnD.dbo.Sessions';
$config['dataTableName'] = 'Club_RnD.dbo.SessionData';