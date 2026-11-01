<?php
$db = new PDO('sqlite:' . __DIR__ . '/demo.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS usuarios (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  usuario TEXT,
  password TEXT
)");

$db->exec("INSERT INTO usuarios (usuario, password)
  SELECT 'admin', 'admin123'
  WHERE NOT EXISTS (SELECT 1 FROM usuarios)");