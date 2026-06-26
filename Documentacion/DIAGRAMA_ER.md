# Diagrama Entidad-Relación (ER) - Base de Datos Videojuegos

Este diagrama muestra la estructura relacional de la base de datos de la aplicación de gestión de trofeos PS4/PS5.

```mermaid
erDiagram
    admins ||--o{ juegos : "gestiona"
    juegos ||--o{ trofeos : "tiene"
    juegos ||--o{ dlcs : "contiene"
    juegos ||--o{ media_juegos : "tiene"
    juegos ||--|| progreso_trofeos : "registra progreso"
    dlcs ||--o{ trofeos_dlc : "tiene"
    dlcs ||--o{ media_dlcs : "tiene"

    admins {
        int id PK
        varchar usuario UK
        varchar password_hash
        timestamp creado_en
    }

    juegos {
        int id PK
        varchar titulo
        enum plataforma
        date fecha_lanzamiento
        varchar genero
        varchar desarrollador
        varchar imagen_url
        varchar banner_url
        float banner_crop_x
        float banner_crop_y
        float banner_crop_width
        float banner_crop_height
        varchar pegi_url
        varchar clasificacion_1_url
        varchar clasificacion_2_url
        varchar clasificacion_3_url
        tinyint show_clas1
        tinyint show_clas2
        tinyint show_clas3
        timestamp fecha_adicionado
        varchar dificultad_platino
        varchar duracion_estimada
        smallint trofeos_offline_platino
        smallint trofeos_offline_oro
        smallint trofeos_offline_plata
        smallint trofeos_offline_bronce
        smallint trofeos_online_platino
        smallint trofeos_online_oro
        smallint trofeos_online_plata
        smallint trofeos_online_bronce
        smallint total_trofeos
        tinyint pase_online
        varchar necesario_platino
        text trofeos_ocultos
        varchar min_partidas
        text trofeos_perdibles
        tinyint trucos_afectan
        tinyint dificultad_afecta
        text comentario
        varchar mapa_interactivo_url
    }

    trofeos {
        int id PK
        int videojuego_id FK
        varchar nombre_trofeo
        text descripcion
        enum tipo
        tinyint conseguido
        tinyint online
        tinyint perdible
        varchar icono_url
        text instrucciones
        varchar video_url
    }

    dlcs {
        int id PK
        int videojuego_id FK
        varchar nombre
        date fecha_lanzamiento
        text descripcion
        varchar imagen_url
        varchar banner_url
        varchar dificultad_platino
        varchar duracion_estimada
        int trofeos_offline_oro
        int trofeos_offline_plata
        int trofeos_offline_bronce
        int trofeos_online_oro
        int trofeos_online_plata
        int trofeos_online_bronce
        text trofeos_perdibles
        tinyint trucos_afectan
        tinyint dificultad_afecta
        text comentario
        timestamp fecha_adicionado
    }

    trofeos_dlc {
        int id PK
        int dlc_id FK
        varchar nombre_trofeo
        text descripcion
        enum tipo
        tinyint conseguido
        tinyint online
        tinyint perdible
        varchar icono_url
        text instrucciones
        varchar video_url
    }

    media_juegos {
        int id PK
        int videojuego_id FK
        enum tipo
        varchar url
        varchar titulo
        int orden
        timestamp fecha_adicionado
    }

    media_dlcs {
        int id PK
        int dlc_id FK
        enum tipo
        varchar url
        varchar titulo
        int orden
        timestamp fecha_adicionado
    }

    progreso_trofeos {
        int id PK
        int videojuego_id FK
        int total_trofeos
        int bronce_conseguidos
        int plata_conseguidos
        int oro_conseguidos
        tinyint platino_conseguido
        decimal porcentaje_completado
        timestamp ultima_actualizacion
    }
```

## Descripción de las Relaciones

### admins → juegos
- **Relación**: Uno a muchos (1:N)
- **Descripción**: Un administrador puede gestionar múltiples videojuegos
- **Cardinalidad**: Un admin puede tener 0 o muchos juegos

### juegos → trofeos
- **Relación**: Uno a muchos (1:N)
- **Descripción**: Un videojuego puede tener múltiples trofeos
- **Cardinalidad**: Un juego tiene 0 o muchos trofeos
- **Cascade**: Al eliminar un juego, se eliminan sus trofeos

### juegos → dlcs
- **Relación**: Uno a muchos (1:N)
- **Descripción**: Un videojuego puede tener múltiples DLCs
- **Cardinalidad**: Un juego tiene 0 o muchos DLCs
- **Cascade**: Al eliminar un juego, se eliminan sus DLCs

### juegos → media_juegos
- **Relación**: Uno a muchos (1:N)
- **Descripción**: Un videojuego puede tener múltiples elementos multimedia
- **Cardinalidad**: Un juego tiene 0 o muchos elementos multimedia
- **Cascade**: Al eliminar un juego, se elimina su multimedia

### juegos → progreso_trofeos
- **Relación**: Uno a uno (1:1)
- **Descripción**: Cada videojuego tiene un registro único de progreso
- **Cardinalidad**: Un juego tiene exactamente un registro de progreso
- **Unique**: videojuego_id es único en progreso_trofeos

### dlcs → trofeos_dlc
- **Relación**: Uno a muchos (1:N)
- **Descripción**: Un DLC puede tener múltiples trofeos
- **Cardinalidad**: Un DLC tiene 0 o muchos trofeos
- **Cascade**: Al eliminar un DLC, se eliminan sus trofeos

### dlcs → media_dlcs
- **Relación**: Uno a muchos (1:N)
- **Descripción**: Un DLC puede tener múltiples elementos multimedia
- **Cardinalidad**: Un DLC tiene 0 o muchos elementos multimedia
- **Cascade**: Al eliminar un DLC, se elimina su multimedia

## Tipos de Datos Utilizados

- **INT**: Enteros para IDs y contadores
- **VARCHAR**: Cadenas de texto de longitud variable
- **TEXT**: Texto largo para descripciones y comentarios
- **ENUM**: Valores predefinidos (plataforma, tipo de trofeo, tipo de media)
- **DATE**: Fechas (lanzamiento)
- **TIMESTAMP**: Fechas y horas automáticas
- **TINYINT**: Valores booleanos (0/1)
- **SMALLINT**: Números pequeños (contadores de trofeos)
- **FLOAT**: Números decimales (coordenadas de crop)
- **DECIMAL**: Números decimales precisos (porcentajes)

## Índices y Restricciones

### Índices
- **idx_titulo**: Índice en juegos.titulo para búsquedas
- **idx_plataforma**: Índice en juegos.plataforma para filtrado
- **idx_fecha**: Índice en juegos.fecha_lanzamiento
- **idx_videojuego_id**: Índices en tablas hijas para joins
- **idx_orden**: Índices en tablas de media para ordenamiento

### Foreign Keys
- Todas las relaciones tienen foreign keys con CASCADE DELETE
- Garantiza integridad referencial
- Eliminación en cascada automática

### Unique Keys
- admins.usuario: Nombre de usuario único
- progreso_trofeos.videojuego_id: Progreso único por juego

## Cómo usar este diagrama

1. Copia el código Mermaid del bloque de código
2. Visita https://www.mermaideditor.io/diagrams/er
3. Pega el código en el editor
4. El diagrama se generará automáticamente
5. Puedes exportarlo como PNG, SVG o PDF
