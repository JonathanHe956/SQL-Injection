<?php require __DIR__ . '/db.php';
$mensaje = null;
$ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario  = $_POST['usuario'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($usuario === '' || $password === '' || !entrada_valida($usuario, 100) || !entrada_valida($password, 100)) {
    $mensaje = 'Datos incorrectos';
  } else {
    try {
      $stmt = $db->prepare('SELECT * FROM usuarios WHERE usuario = ?');
      $stmt->execute([$usuario]);
      $fila = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($fila && password_verify($password, $fila['password'])) {
        $ok = true;
        $mensaje = 'Acceso concedido';
      } else {
        $mensaje = 'Datos incorrectos';
      }
    } catch (PDOException $e) {
      seguro_log('Login: ' . $e->getMessage());
      $mensaje = 'Ocurrió un error, intenta de nuevo.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TiendaLab — Iniciar sesión</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;color:#1a1a1a;background:#f4f5f7;margin:0}
  .topbar{background:#fff;border-bottom:2px solid #1a56db;padding:12px 20px}
  .topbar .brand{font-weight:bold;font-size:18px;color:#1a56db;margin-right:20px}
  .topbar a{color:#1a56db;font-size:14px;margin-right:14px;text-decoration:none}
  .topbar a:hover{text-decoration:underline}
  .wrap{max-width:440px;margin:40px auto;padding:0 16px}
  .aviso{border:1px solid #ccc;border-radius:2px;background:#fff;padding:10px 12px;font-size:13px;color:#444;margin-bottom:20px}
  .card{background:#fff;border:1px solid #ccc;border-radius:2px;padding:24px}
  .logo{font-weight:bold;font-size:20px;color:#1a56db;margin-bottom:18px;text-align:center}
  label{display:block;font-size:13px;color:#333;margin:12px 0 4px}
  input{width:100%;padding:8px;border:1px solid #bbb;border-radius:2px;font-size:14px;box-sizing:border-box}
  button{width:100%;margin-top:18px;padding:10px;border:1px solid #1a56db;border-radius:2px;background:#1a56db;color:#fff;font-size:15px;cursor:pointer}
  .alert{padding:9px 12px;border:1px solid #ccc;border-radius:2px;margin-bottom:16px;font-size:14px}
  .alert.ok{background:#e7f6ec;border-color:#2e7d32;color:#1b5e20}
  .alert.err{background:#fbeaea;border-color:#c62828;color:#8e1b1b}
  .hint{margin-top:16px;font-size:13px;color:#777;text-align:center}
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
    <form class="card" method="POST">
      <div class="logo">TiendaLab</div>

      <?php if ($mensaje !== null): ?>
        <div class="alert <?= $ok ? 'ok' : 'err' ?>"><?= htmlspecialchars($mensaje) ?></div>
      <?php endif; ?>

      <label for="usuario">Usuario</label>
      <input id="usuario" name="usuario" autocomplete="off">

      <label for="password">Contraseña</label>
      <input id="password" name="password" type="password">

      <button type="submit">Ingresar</button>

      <p class="hint">¿Olvidaste tu contraseña?</p>
    </form>
  </div>
</body>
</html>
