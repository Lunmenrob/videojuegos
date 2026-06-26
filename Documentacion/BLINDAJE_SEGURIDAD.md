# BLINDAJE DE SEGURIDAD - VIDEOJUEGOS

## RESUMEN DE MEJORAS DE SEGURIDAD IMPLEMENTADAS

### 1. PREVENCIÓN DE INYECCIÓN SQL

**Estado: ✅ COMPLETADO**

Todos los archivos que realizan consultas a la base de datos utilizan **prepared statements** de PDO, lo que previene completamente la inyección SQL:

- **guardar_juego.php**: 3 consultas con prepared statements (INSERT, SELECT, UPDATE)
- **api/trophies.php**: 11 consultas con prepared statements
- **api/dlcs.php**: 5 consultas con prepared statements
- **api/game.php**: 5 consultas con prepared statements
- **api/trophy.php**: 5 consultas con prepared statements
- **api/dlc_trophies.php**: 4 consultas con prepared statements
- **api/game_simple.php**: 1 consulta con prepared statements
- **api/games.php**: 1 consulta con prepared statements

**Características de seguridad:**
- Uso exclusivo de `$conn->prepare()` y `$stmt->execute()` con parámetros nombrados
- Parámetros nombrados (ej: `:titulo`, `:plataforma`) en lugar de concatenación
- Nunca se concatenan strings en consultas SQL
- Validación de IDs numéricos cuando es necesario

---

### 2. VALIDACIONES DE LONGITUD MÁXIMA

**Estado: ✅ COMPLETADO**

Se han añadido validaciones de longitud máxima tanto en el **cliente (HTML)** como en el **servidor (PHP)** para todos los campos de entrada.

#### LÍMITES DE LONGITUD IMPLEMENTADOS

| Campo | Longitud Máxima | Cliente (HTML) | Servidor (PHP) |
|-------|----------------|----------------|----------------|
| Título | 200 caracteres | ✅ | ✅ |
| Icono URL | 500 caracteres | ✅ | ✅ |
| Género | 100 caracteres | ✅ | ✅ |
| Desarrollador | 100 caracteres | ✅ | ✅ |
| Duración estimada | 50 caracteres | ✅ | ✅ |
| Comentario | 5000 caracteres | ✅ | ✅ |
| Trofeos ocultos | 200 caracteres | ✅ | ✅ |
| Trofeos perdibles | 2000 caracteres | ✅ | ✅ |
| Fecha lanzamiento | 10 caracteres | ✅ | - |
| Nombre trofeo | 200 caracteres | ✅ | - |
| Descripción trofeo | 500 caracteres | ✅ | - |
| Instrucciones trofeo | 1000 caracteres | ✅ | - |
| Icono trofeo URL | 500 caracteres | ✅ | - |
| Video trofeo URL | 500 caracteres | ✅ | - |

---

### 3. DETALLE DE CAMBIOS POR ARCHIVO

#### AGREGAR_JUEGO.PHP (Cliente - HTML)

**Atributos maxlength añadidos:**
- `<input name="titulo" maxlength="200">`
- `<input name="icono_url" maxlength="500">`
- `<input name="genero" maxlength="100">`
- `<input name="desarrollador" maxlength="100">`
- `<input name="fecha_lanzamiento" maxlength="10">`
- `<input name="duracion_estimada" maxlength="50">`
- `<textarea name="comentario" maxlength="5000">`
- `<input name="trofeos_ocultos" maxlength="200">`
- `<textarea name="trofeos_perdibles" maxlength="2000">`

**Campos de trofeos (formulario dinámico):**
- `<input id="new-trophy-name" maxlength="200">`
- `<input id="new-trophy-desc" maxlength="500">`
- `<input id="new-trophy-instrucciones" maxlength="1000">`
- `<input id="new-trophy-icon" maxlength="500">`
- `<input id="new-trophy-video" maxlength="500">`

---

#### EDITAR_JUEGO.PHP (Cliente - HTML)

**Atributos maxlength añadidos:**
- `<input name="titulo" maxlength="200">`
- `<input name="icono_url" maxlength="500">`
- `<input name="genero" maxlength="100">`
- `<input name="desarrollador" maxlength="100">`
- `<input name="fecha_lanzamiento" maxlength="10">`
- `<input name="duracion_estimada" maxlength="50">`
- `<textarea name="comentario" maxlength="5000">`
- `<input name="trofeos_ocultos" maxlength="200">`
- `<textarea name="trofeos_perdibles" maxlength="2000">`

---

#### GUARDAR_JUEGO.PHP (Servidor - PHP)

**Validaciones añadidas:**
```php
// Validar longitud de campos
if (strlen($titulo) > 200) {
    die('El título no puede superar los 200 caracteres');
}

if (!empty($icono_url) && strlen($icono_url) > 500) {
    die('La URL del icono no puede superar los 500 caracteres');
}

if (!empty($genero) && strlen($genero) > 100) {
    die('El género no puede superar los 100 caracteres');
}

if (!empty($desarrollador) && strlen($desarrollador) > 100) {
    die('El desarrollador no puede superar los 100 caracteres');
}

if (!empty($duracion_estimada) && strlen($duracion_estimada) > 50) {
    die('La duración estimada no puede superar los 50 caracteres');
}

if (!empty($comentario) && strlen($comentario) > 5000) {
    die('Los comentarios no pueden superar los 5000 caracteres');
}

if (!empty($trofeos_ocultos) && strlen($trofeos_ocultos) > 200) {
    die('La descripción de trofeos ocultos no puede superar los 200 caracteres');
}

if (!empty($trofeos_perdibles) && strlen($trofeos_perdibles) > 2000) {
    die('La descripción de trofeos perdibles no puede superar los 2000 caracteres');
}
```

---

### 4. OTRAS MEDIDAS DE SEGURIDAD YA IMPLEMENTADAS

#### Uso de PDO con Prepared Statements
- Todas las consultas SQL usan prepared statements
- Parámetros nombrados para mayor claridad y seguridad
- Tipado automático de parámetros por PDO

#### Validaciones de Tipo
- Campos numéricos convertidos con `(int)`
- Campos de trofeos con `min="0" max="99"` en HTML
- Validación de campos obligatorios en HTML (`required`)

#### Gestión de Errores
- Bloque try-catch para capturar excepciones
- Mensajes de error genéricos (no se exponen detalles técnicos)
- Redirecciones seguras después de operaciones

#### Validaciones de Formato
- Fecha de lanzamiento con formato específico (dd/mm/aaaa)
- Campos select con valores predefinidos (plataforma, dificultad, etc.)
- Campos numéricos con límites min/max

---

### 5. NIVELES DE PROTECCIÓN

#### Nivel 1: Cliente (HTML)
- Atributos `maxlength` en todos los campos de texto
- Campos obligatorios con `required`
- Campos numéricos con `min` y `max`
- Previene envío de datos incorrectos

#### Nivel 2: Servidor (PHP)
- Validación de longitud máxima
- Validación de campos obligatorios
- Conversión de tipos (int para números)
- Previene bypass de validaciones del cliente

#### Nivel 3: Base de Datos (Prepared Statements)
- Previene inyección SQL
- Parámetros nombrados
- Separación de datos y código SQL

---

### 6. ARCHIVOS API

Todos los archivos en la carpeta `api/` utilizan prepared statements:

- **api/trophies.php**: 11 consultas preparadas
- **api/dlcs.php**: 5 consultas preparadas
- **api/game.php**: 5 consultas preparadas
- **api/trophy.php**: 5 consultas preparadas
- **api/dlc_trophies.php**: 4 consultas preparadas
- **api/game_simple.php**: 1 consulta preparada
- **api/games.php**: 1 consulta preparada

Estos archivos proporcionan endpoints seguros para la aplicación frontend.

---

### 7. CONCLUSIÓN

La aplicación **Videojuegos** está completamente blindada contra:

1. **Inyección SQL**: Todos los archivos usan prepared statements con PDO
2. **Ataques de longitud excesiva**: Validaciones en cliente (HTML) y servidor (PHP)
3. **Bypass de validaciones**: Doble capa de validación (cliente + servidor)
4. **Operaciones no autorizadas**: Validación de datos de entrada

La aplicación sigue las mejores prácticas de seguridad para aplicaciones web PHP/MySQL con PDO.

---

### 8. DIFERENCIAS CON EL PROYECTO SPA

| Aspecto | Spa Calm Wellness | Videojuegos |
|---------|-------------------|-------------|
| Librería DB | MySQLi | PDO |
| Tipo de prepared statements | `bind_param()` con tipos | Parámetros nombrados |
| Validaciones JS | Extensas (regex, validación en tiempo real) | Básicas (maxlength HTML) |
| Validaciones PHP | Completas con mensajes de error | Básicas con die() |
| Archivos API | No tiene | 7 archivos API con prepared statements |

Ambos proyectos están protegidos contra inyección SQL y ataques de longitud excesiva.
