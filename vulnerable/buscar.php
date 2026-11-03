<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
require __DIR__ . '/../db/db.php';

$q = $_GET['q'] ?? null;
$productos = null;
if ($q !== null) {
  $sql = "SELECT nombre, descripcion, precio FROM productos WHERE nombre LIKE '%$q%'";
  $productos = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TiendaLab — Buscar productos</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;color:#1a1a1a;background:#f4f5f7;margin:0}
  .topbar{background:#fff;border-bottom:2px solid #1a56db;padding:12px 20px}
  .topbar .brand{font-weight:bold;font-size:18px;color:#1a56db;margin-right:20px}
  .topbar a{color:#1a56db;font-size:14px;margin-right:14px;text-decoration:none}
  .topbar a:hover{text-decoration:underline}
  .wrap{max-width:760px;margin:40px auto;padding:0 16px}
  .aviso{border:1px solid #ccc;border-radius:2px;background:#fff;padding:10px 12px;font-size:13px;color:#444;margin-bottom:20px}
  .card{background:#fff;border:1px solid #ccc;border-radius:2px;padding:24px}
  h1{font-size:20px;color:#1a56db;margin:0 0 18px}
  form{margin-bottom:20px}
  input{padding:8px;border:1px solid #bbb;border-radius:2px;font-size:14px;width:300px;max-width:100%}
  button{margin-left:6px;padding:8px 16px;border:1px solid #1a56db;border-radius:2px;background:#1a56db;color:#fff;font-size:14px;cursor:pointer}
  .resumen{font-size:13px;color:#555;margin-bottom:12px}
  .item{padding:12px 0;border-top:1px solid #e2e2e2}
  .item h3{font-size:15px;margin:0 0 4px}
  .item p{font-size:13px;color:#444;margin:0}
  .item .precio{font-weight:bold;color:#1a56db;margin-top:4px}
  .vacio{font-size:14px;color:#555}
  .alert.err{padding:9px 12px;border:1px solid #c62828;border-radius:2px;background:#fbeaea;color:#8e1b1b;margin-bottom:16px;font-size:14px}
</style>
</head>
<body>
  <div class="topbar">
    <span class="brand">TiendaLab</span>
    <a href="index.php">Inicio</a>
    <a href="buscar.php">Buscar productos</a>
    <a href="#">Ayuda</a>
    <a href="#">Mi carrito</a>
  </div>

  <div class="wrap">
    <div class="card">
      <h1>Buscar productos</h1>
      <form method="GET">
        <input name="q" autocomplete="off" value="<?= htmlspecialchars($q ?? '') ?>">
        <button type="submit">Buscar</button>
      </form>

      <?php if ($productos !== null): ?>
        <div class="resumen"><?= count($productos) ?> resultado(s) para "<?= htmlspecialchars($q) ?>"</div>
        <?php if (!$productos): ?>
          <p class="vacio">No se encontraron productos.</p>
        <?php endif; ?>
        <?php foreach ($productos as $p): ?>
          <div class="item">
            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
            <p><?= htmlspecialchars($p['descripcion']) ?></p>
            <p class="precio">$<?= htmlspecialchars($p['precio']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
