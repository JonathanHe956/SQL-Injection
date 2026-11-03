# TiendaLab — Laboratorio de Inyección SQL (OWASP Top 10)

Laboratorio académico de buenas prácticas de codificación sobre la categoría
**Injection** del OWASP Top 10. Compara una versión **vulnerable** (consultas
armadas por concatenación) con una versión **corregida** (consultas preparadas,
`password_hash`, errores genéricos y validación de entrada).

> Entorno de laboratorio. Solo para uso en **localhost**, con datos y usuarios
> **ficticios**. No usar contra sistemas de terceros.

## Estructura

| Carpeta | Contenido |
|---------|-----------|
| `vulnerable/` | App con fallas intencionales (`index.php`, `buscar.php`). Puerto 8000. |
| `seguro/` | App corregida (`index.php`, `buscar.php`, `db.php`, `init_db.php`). Puerto 8001. |
| `db/` | Base de la versión vulnerable (`db.php`, `init_db.php`). |
| `herramientas/` | `PENTEST.md` con los casos de prueba y la remediación. |
| `evidencia/` | Salidas numeradas de cada prueba y `RESUMEN.md`. |

## Requisitos

- **PHP 8+** con la extensión **pdo_sqlite** (verifica con `php -m | grep sqlite`).
- **sqlmap** para las pruebas automatizadas (`pip install sqlmap`).

## 1. Generar las bases de datos

Las bases (`*.db`) no se versionan; se generan localmente:

```
php db/init_db.php
php seguro/init_db.php
```

- `db/init_db.php` crea `db/tiendalab.db` (contraseñas en texto plano, a propósito).
- `seguro/init_db.php` crea `seguro/tiendalab_seguro.db` (contraseñas con `password_hash`).

Ambas tienen los mismos 5 usuarios y 10 productos.

## 2. Levantar las dos versiones a la vez

En dos terminales distintas:

```
php -S 127.0.0.1:8000 -t vulnerable
php -S 127.0.0.1:8001 -t seguro
```

- Vulnerable: http://127.0.0.1:8000/
- Corregida:  http://127.0.0.1:8001/

Usuarios de prueba: `admin`/`admin123`, `ana`/`ana2024`, `carlos`/`carlos#99`,
`vendedor`/`venta$123`, `soporte`/`soporte!1`.

## 3. Casos de prueba

Cambia el puerto (`8000` = vulnerable, `8001` = seguro) para comparar. En
`/vulnerable` los ataques funcionan; en `/seguro` no.

### Manuales (login)

- Login normal: usuario `ana`, contraseña `ana2024`.
- Bypass de login: usuario `' OR '1'='1' -- `, contraseña vacía.

### Manuales (buscador, en el navegador)

- Búsqueda normal: `http://127.0.0.1:8000/buscar.php?q=mouse`
- Comilla simple: `http://127.0.0.1:8000/buscar.php?q='`
- UNION SELECT: `http://127.0.0.1:8000/buscar.php?q=zzz%' UNION SELECT usuario,password,rol FROM usuarios -- `

### sqlmap

Vulnerable (puerto 8000):

```
sqlmap -u "http://127.0.0.1:8000/buscar.php?q=mouse" -p q --batch --dbms=SQLite --technique=BU --level=3 --risk=2 --tables --dump -T usuarios
sqlmap -u "http://127.0.0.1:8000/index.php" --data="usuario=admin&password=x" -p usuario --batch --dbms=SQLite --technique=B --level=3 --risk=2 --dbs --tables --dump -T usuarios
```

Corregida (puerto 8001, debe reportar que no es inyectable):

```
sqlmap -u "http://127.0.0.1:8001/buscar.php?q=mouse" -p q --batch --dbms=SQLite --technique=BU --level=3 --risk=2 --flush-session --tables --dump -T usuarios
sqlmap -u "http://127.0.0.1:8001/index.php" --data="usuario=admin&password=x" -p usuario --batch --dbms=SQLite --technique=B --level=3 --risk=2 --flush-session --tables --dump -T usuarios
```

## 4. Evidencia

| Archivo | Prueba | Versión |
|---------|--------|---------|
| `evidencia/01_login_sin_credenciales.txt` | Bypass de login | vulnerable (8000) |
| `evidencia/02_respuesta_comilla.txt` | Comilla simple (error PDO) | vulnerable (8000) |
| `evidencia/03_columnas_union.txt` | Número de columnas con UNION | vulnerable (8000) |
| `evidencia/04_datos_expuestos.txt` | UNION SELECT a usuarios | vulnerable (8000) |
| `evidencia/05_sqlmap_buscar.txt` | sqlmap buscador (GET) | vulnerable (8000) |
| `evidencia/06_sqlmap_login.txt` | sqlmap login (POST) | vulnerable (8000) |
| `evidencia/07_retest_login.txt` | Retest bypass de login | seguro (8001) |
| `evidencia/08_retest_comilla.txt` | Retest comilla simple | seguro (8001) |
| `evidencia/09_retest_union.txt` | Retest UNION SELECT | seguro (8001) |
| `evidencia/10_retest_sqlmap_buscar.txt` | Retest sqlmap buscador (GET) | seguro (8001) |
| `evidencia/11_retest_sqlmap_login.txt` | Retest sqlmap login (POST) | seguro (8001) |
| `evidencia/RESUMEN.md` | Tabla comparativa | ambas |

La remediación aplicada, falla por falla, está en `herramientas/PENTEST.md`
(sección 5).
