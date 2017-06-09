<?php

/**
 * Our database credentials.
 */
$config = array(
  'host'		=> '',
  'username'	=> 'root',
  'password'	=> '', // Hidden
  'dbname' 	=> 'andreiho_xmldb'
) ;

/**
 * Attempt connection to the database using the defined credentials.
 */
try {
  $db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
}
catch(PDOException $e)
{
  echo $e->getMessage();
}

/**
 * Set the error mode.
 */
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
