<?php
ini_set('display_errors', '0');

function seguro_log($mensaje) {
  $linea = '[' . date('Y-m-d H:i:s') . '] ' . $mensaje . PHP_EOL;
  error_log($linea, 3, __DIR__ . '/error.log');
}

function entrada_valida($valor, $max = 100) {
  if (!is_string($valor)) {
    return false;
  }
  if (mb_strlen($valor) > $max) {
    return false;
  }
  if (preg_match('/[\x00-\x1F\x7F]/u', $valor)) {
    return false;
  }
  return true;
}

$rutaDb = __DIR__ . '/tiendalab_seguro.db';
if (!file_exists($rutaDb)) {
  die('No existe la base de datos. Ejecuta primero: php seguro/init_db.php');
}

try {
  $db = new PDO('sqlite:' . $rutaDb);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  seguro_log('Conexion: ' . $e->getMessage());
  http_response_code(500);
  die('Ocurrió un error, intenta de nuevo.');
}
