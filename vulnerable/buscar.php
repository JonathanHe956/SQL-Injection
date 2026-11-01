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
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%232f5bff'/%3E%3Ctext x='16' y='22' font-family='Arial' font-size='16' font-weight='700' fill='white' text-anchor='middle'%3ETL%3C/text%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<title>TiendaLab — Buscar productos</title>
<style>
  :root{
    --card:rgba(20,30,48,.72);
    --border:rgba(255,255,255,.10);
    --txt:#e8edf5;
    --muted:#9fb0c7;
    --accent:#2f5bff;
    --accent-hover:#2449d8;
    --err:#b4324a;
  }
  *{box-sizing:border-box;margin:0;padding:0}
  body{
    min-height:100vh;
    font-family:"Poppins","Segoe UI",system-ui,Arial,sans-serif;
    color:var(--txt);
    background:
      radial-gradient(circle at 20% 20%,rgba(47,91,255,.25),transparent 45%),
      radial-gradient(circle at 80% 80%,rgba(31,138,76,.18),transparent 45%),
      #0b0d12;
    padding:96px 16px 32px;
  }
  .topbar{
    position:fixed;top:0;left:0;right:0;height:72px;z-index:50;
    background:#223a86;
    display:flex;align-items:center;gap:36px;
    padding:0 28px;
    box-shadow:0 2px 12px rgba(0,0,0,.35);
  }
  .brand{font-weight:700;font-size:20px;letter-spacing:.5px;color:#fff;text-decoration:none}
  .nav{display:flex;align-items:center;gap:30px}
  .nav a{color:#e5e7eb;font-size:14px;font-weight:600;text-decoration:none}
  .nav a:hover{color:#9ca3af}
  .card{
    width:100%;max-width:720px;margin:0 auto;
    background:var(--card);
    border:1px solid var(--border);
    border-radius:16px;
    padding:32px;
    backdrop-filter:blur(10px);
    box-shadow:0 20px 60px rgba(0,0,0,.45);
  }
  h1{font-size:22px;margin-bottom:18px}
  form{display:flex;gap:10px;margin-bottom:24px}
  input{
    flex:1;min-width:0;padding:14px 16px;
    background:rgba(255,255,255,.04);
    border:1px solid var(--border);
    border-radius:10px;
    color:var(--txt);font-size:15px;
  }
  input::placeholder{color:#6f829c}
  input:focus{outline:none;border-color:var(--accent);background:rgba(47,91,255,.08)}
  button{
    padding:14px 22px;
    background:var(--accent);
    color:#fff;font-size:15px;font-weight:600;
    border:none;border-radius:10px;cursor:pointer;
  }
  button:hover{background:var(--accent-hover)}
  .resumen{font-size:13px;color:var(--muted);margin-bottom:12px}
  .item{padding:14px 0;border-top:1px solid var(--border);display:flex;justify-content:space-between;gap:16px}
  .item h3{font-size:16px;margin-bottom:4px}
  .item p{font-size:13px;color:var(--muted)}
  .precio{font-weight:600;white-space:nowrap}
  .vacio{font-size:14px;color:var(--muted)}
  .aviso{
    position:fixed;right:14px;bottom:14px;z-index:60;
    background:rgba(180,50,74,.92);
    color:#fff;font-size:12px;line-height:1.3;
    padding:8px 12px;border-radius:8px;max-width:260px;
    box-shadow:0 6px 18px rgba(0,0,0,.4);
  }
</style>
</head>
<body>
  <header class="topbar">
    <a class="brand" href="index.php">TiendaLab</a>
    <nav class="nav">
      <a href="index.php">Inicio</a>
      <a href="buscar.php">Buscar productos</a>
    </nav>
  </header>

  <div class="aviso">TiendaLab es una marca ficticia. Página de prueba en entorno de laboratorio (localhost).</div>

  <main class="card">
    <h1>Buscar productos</h1>
    <form method="GET">
      <input name="q" placeholder="Nombre del producto" autocomplete="off" value="<?= htmlspecialchars($q ?? '') ?>">
      <button type="submit">Buscar</button>
    </form>

    <?php if ($productos !== null): ?>
      <div class="resumen"><?= count($productos) ?> resultado(s) para "<?= htmlspecialchars($q) ?>"</div>
      <?php if (!$productos): ?>
        <p class="vacio">No se encontraron productos.</p>
      <?php endif; ?>
      <?php foreach ($productos as $p): ?>
        <div class="item">
          <div>
            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
            <p><?= htmlspecialchars($p['descripcion']) ?></p>
          </div>
          <div class="precio">$<?= htmlspecialchars($p['precio']) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>
</body>
</html>
