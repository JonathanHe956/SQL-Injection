<?php
$rutaDb = __DIR__ . '/tiendalab.db';
if (!file_exists($rutaDb)) {
  die('No existe la base de datos. Ejecuta primero: php db/init_db.php');
}

$db = new PDO('sqlite:' . $rutaDb);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
