# RESUMEN — Comparativa /vulnerable vs /seguro

Laboratorio académico TiendaLab (OWASP Top 10 — Injection). Solo localhost, datos
y usuarios ficticios.

- **/vulnerable** corre en el puerto **8000** (consultas por concatenación).
- **/seguro** corre en el puerto **8001** (consultas preparadas, `password_hash`,
  errores genéricos, validación de entrada).

## Casos de prueba

| Caso de prueba | /vulnerable (8000) | /seguro (8001) |
|----------------|--------------------|----------------|
| Login legítimo (`ana` / `ana2024`) | Acceso concedido | Acceso concedido |
| Búsqueda normal (`mouse`) | 1 resultado (Mouse Vector) | 1 resultado (Mouse Vector) |
| Comilla simple en el buscador (`'`) | Error de PDO visible en pantalla | Sin error, texto literal, 0 resultados |
| Bypass de login (`' OR '1'='1' -- `) | Acceso concedido (sin credenciales) | Datos incorrectos (rechazado) |
| Número de columnas (`UNION SELECT 1,2,3`) | Revela que hay 3 columnas | Sin diferencia, texto literal |
| UNION SELECT a `usuarios` | Vuelca usuario, password y rol | 0 resultados, sin fuga |
| Blind booleano (`AND 1=1` vs `AND 1=2`) | Respuesta diferencial | Igual respuesta (no diferencial) |
| sqlmap buscador (GET, parámetro `q`) | Inyectable: UNION query, vuelca `usuarios` | No inyectable |
| sqlmap login (POST, parámetro `usuario`) | Inyectable: boolean-based blind, vuelca `usuarios` | No inyectable |
| Almacenamiento de contraseñas | Texto plano (expuesto en el volcado) | Hash con `password_hash` |

## Archivos de evidencia

| Archivo | Prueba | Objetivo |
|---------|--------|----------|
| `01_login_sin_credenciales.txt` | Bypass de login manual | /vulnerable 8000 |
| `02_respuesta_comilla.txt` | Comilla simple (error PDO) | /vulnerable 8000 |
| `03_columnas_union.txt` | Número de columnas con UNION | /vulnerable 8000 |
| `04_datos_expuestos.txt` | UNION SELECT a usuarios | /vulnerable 8000 |
| `05_sqlmap_buscar.txt` | sqlmap buscador (GET) | /vulnerable 8000 |
| `06_sqlmap_login.txt` | sqlmap login (POST) | /vulnerable 8000 |
| `07_retest_login.txt` | Retest bypass de login | /seguro 8001 |
| `08_retest_comilla.txt` | Retest comilla simple | /seguro 8001 |
| `09_retest_union.txt` | Retest UNION SELECT | /seguro 8001 |
| `10_retest_sqlmap_buscar.txt` | Retest sqlmap buscador (GET) | /seguro 8001 |
| `11_retest_sqlmap_login.txt` | Retest sqlmap login (POST) | /seguro 8001 |

Nota: `10` y `11` contienen la salida real de sqlmap contra el puerto 8001; en
ambos casos sqlmap reporta que el parámetro no es inyectable y aborta sin volcar
ninguna tabla.
