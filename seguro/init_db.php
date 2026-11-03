<?php
$rutaDb = __DIR__ . '/tiendalab_seguro.db';
if (file_exists($rutaDb)) {
  unlink($rutaDb);
}

$db = new PDO('sqlite:' . $rutaDb);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE usuarios (
  id       INTEGER PRIMARY KEY AUTOINCREMENT,
  usuario  TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  rol      TEXT NOT NULL
)");

$db->exec("CREATE TABLE productos (
  id          INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre      TEXT NOT NULL,
  descripcion TEXT,
  categoria   TEXT,
  precio      REAL NOT NULL,
  stock       INTEGER NOT NULL DEFAULT 0
)");

$usuarios = [
  ['admin',   'admin123',   'admin'],
  ['ana',     'ana2024',    'cliente'],
  ['carlos',  'carlos#99',  'cliente'],
  ['vendedor','venta$123',  'vendedor'],
  ['soporte', 'soporte!1',  'soporte'],
];

$productos = [
  ['Audífonos Nébula',     'Audífonos inalámbricos con cancelación de ruido', 'Audio',      899.00, 25],
  ['Bocina Pulso Mini',    'Bocina Bluetooth portátil resistente al agua',     'Audio',      549.50, 40],
  ['Teclado Kairo',        'Teclado mecánico compacto con retroiluminación',  'Accesorios', 1250.00, 15],
  ['Mouse Vector',         'Mouse óptico inalámbrico de 6 botones',            'Accesorios', 399.00, 60],
  ['Monitor Prisma 24"',   'Monitor IPS Full HD de 24 pulgadas',               'Pantallas',  3299.00, 8],
  ['Webcam Orbe HD',       'Cámara web 1080p con micrófono integrado',         'Accesorios', 749.00, 30],
  ['Memoria USB Fénix 64', 'Memoria USB 3.0 de 64 GB',                         'Almacenamiento', 159.00, 120],
  ['SSD Rayo 1TB',         'Unidad de estado sólido NVMe de 1 TB',             'Almacenamiento', 1499.00, 20],
  ['Cargador Volta 65W',   'Cargador USB-C de carga rápida',                   'Energía',    489.00, 45],
  ['Mochila Ruta',         'Mochila para laptop de hasta 15.6 pulgadas',       'Accesorios', 699.00, 35],
];

$insUsuario = $db->prepare('INSERT INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)');
foreach ($usuarios as $u) {
  $insUsuario->execute([$u[0], password_hash($u[1], PASSWORD_DEFAULT), $u[2]]);
}

$insProducto = $db->prepare('INSERT INTO productos (nombre, descripcion, categoria, precio, stock) VALUES (?, ?, ?, ?, ?)');
foreach ($productos as $p) {
  $insProducto->execute($p);
}

$totalU = $db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
$totalP = $db->query('SELECT COUNT(*) FROM productos')->fetchColumn();
echo "Base creada en $rutaDb\n";
echo "usuarios: $totalU | productos: $totalP (contrasenas con password_hash)\n";
