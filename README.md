# Bucle Workspace v2

Ecosistema modular para la gestión de rutinas, hábitos y tareas recurrentes. El sistema permite construir tableros de control personalizados mediante un lienzo interactivo de bloques dinámicos, redimensionables y anidables.

## 🚀 Requisitos del Sistema
- **PHP**: 8.1+
- **Node.js**: 18+
- **Composer**: Última versión estable
- **Base de Datos**: PostgreSQL (o Supabase) con soporte para tipos de datos JSONB.

## 🛠️ Instalación y Despliegue

### Backend (CodeIgniter 4)
1. Navegue al directorio del backend:
   ```bash
   cd bucle_backend
   ```
2. Instale las dependencias de Composer:
   ```bash
   composer install
   ```
3. Configure el archivo de entorno:
   ```bash
   cp .env.example .env
   ```
   *Edite el archivo `.env` con sus credenciales de base de datos.*
4. Inicie el servidor de desarrollo:
   ```bash
   php spark serve
   ```

### Frontend (Vue 3 + Vite)
1. Navegue al directorio del frontend:
   ```bash
   cd bucle_frontend
   ```
2. Instale las dependencias de Node:
   ```bash
   npm install
   ```
3. Inicie el servidor de desarrollo:
   ```bash
   npm run dev
   ```

## 📂 Estructura del Proyecto (Lógica de Bloques)

La lógica de renderizado dinámico reside en `src/components/dynamic`. A continuación se detalla la jerarquía de componentes clave:

```text
src/components/dynamic/
├── BlockRenderer.vue       # Orquestador principal. Evalúa el 'type' del bloque y renderiza el componente correspondiente.
├── FrameBlock.vue          # Componente contenedor. Implementa recursividad al invocar a BlockRenderer para sus bloques hijos.
├── ChecklistBlock.vue      # Manejo de listas de tareas con estado booleano.
├── CycleBlock.vue          # Visualización de progreso circular (SVG) para metas métricas.
├── ListBlock.vue           # Listas simples de elementos.
├── ReminderBlock.vue       # Gestión de alertas y frecuencias temporales.
└── [Otros Bloques]         # Componentes especializados (Map, Table, Calendar, etc.)
```

## 🔐 Variables de Entorno

### Backend (`bucle_backend/.env`)
| Variable | Descripción | Ejemplo |
| :--- | :--- | :--- |
| `database.default.hostname` | Host de la base de datos | `localhost` |
| `database.default.database` | Nombre de la base de datos | `bucle_db` |
| `database.default.username` | Usuario de PostgreSQL | `postgres` |
| `database.default.password` | Contraseña | `tu_password` |
| `database.default.DBDriver` | Driver de conexión | `Postgre` |

### Frontend (`bucle_frontend/.env`)
| Variable | Descripción | Ejemplo |
| :--- | :--- | :--- |
| `VITE_API_URL` | URL base de la API REST | `http://localhost:8080` |

---

## 📝 Documentación de Bloques y Lógica

### Lógica de Bloques y Subtipos

El sistema implementa un esquema de subtipos para el bloque de texto y un sistema avanzado de ciclos de progreso.

#### Subtipos de Propiedad de Texto (Esquemas dinámicos)
Aunque el backend persiste los datos de forma general, el frontend interpreta el contenido basándose en `SUBTYPE_SCHEMAS`. Los principales subtipos utilizados para modelar propiedades son:

1. **`text`**: Cadena de texto plano.
2. **`number`**: Valor numérico.
3. **`date`**: Fecha con soporte para ISO strings.
4. **`checkbox`**: Estado booleano.
5. **`select`**: Selección única con arreglo de opciones.
6. **`multi-select`**: Selección múltiple.
7. **`status`**: Estado de flujo de trabajo (ej: 'Not Started', 'In Progress').
8. **`people`**: Arreglo de identificadores de usuario.
9. **`file`**: Objeto con URL y nombre del recurso.
10. **`url`**: Validación y renderizado de enlaces.
11. **`email`**: Dirección de correo electrónico.
12. **`phone`**: Número telefónico.
13. **`place`**: Datos de geolocalización o dirección.
14. **`frame`**: Referencia a un contenedor anidado.

#### Sistema de Ciclos de Progreso (`cycle`)
El bloque de ciclo permite trackear métricas acumulativas contra un objetivo (target). Su estructura interna soporta:
- **`current`**: Progreso actual.
- **`target`**: Meta a alcanzar.
- **`unit`**: Unidad de medida (ej: 'km', 'horas').
- **Alertas**: Umbrales de notificación (`reminderThreshold`) para disparar eventos antes de completar el ciclo.

---

## 🔌 Guía de API (CodeIgniter 4)

### Endpoints de Entidades

La API REST está estructurada para manejar la jerarquía de Categorías y Subcategorías (Eventos/Fichas).

#### Categorías (Sidebar)
- **`GET /categorias`**: Obtiene todas las categorías principales.
- **`POST /categorias`**: Crea una nueva categoría.
- **`PUT /categorias/:id`**: Actualiza el nombre o propiedades de la categoría.
- **`DELETE /categorias/:id`**: Elimina la categoría y todas sus subcategorías asociadas.
- **`POST /categorias/duplicate/:id`**: Duplica la categoría y su estructura.

#### Subcategorías (Canvas / Editor)
- **`GET /subcategorias/:categoria_id`**: Obtiene las subcategorías filtradas por la categoría padre.
- **`POST /subcategorias`**: Crea una nueva ficha/evento inicializado con un bloque de título y un bloque de texto vacío.
- **`PUT /subcategorias/:id`**: Endpoint crítico. Actualiza el contenido del canvas (bloques) y los estilos.
- **`DELETE /subcategorias/:id`**: Elimina la ficha de forma permanente.

### Estructura de Payloads (Persistencia JSONB)

El cuerpo de las peticiones `PUT /subcategorias/:id` y `POST /subcategorias` debe estructurarse enviando la configuración de estilos y la recursividad de Frames dentro del objeto `datos_extra`.

#### Ejemplo de Payload completo para actualización:

```json
{
  "nombre": "Rutina de Mantenimiento",
  "emoji": "🔧",
  "color": "#6366f1",
  "tags": ["taller", "rutina"],
  "datos_extra": {
    "config": {
      "fontSize": "text-5xl",
      "textAlign": "text-left",
      "textShadow": true,
      "bgColor": "bg-slate-900"
    },
    "blocks": [
      {
        "id": "title-1620000000000",
        "type": "text",
        "content": "Rutina de Mantenimiento",
        "role": "main-title",
        "style": { "format": "h1", "width": "100%", "height": "auto" }
      },
      {
        "id": "frame-1620000000001",
        "type": "frame",
        "content": {
          "blocks": [
            {
              "id": "checklist-1620000000002",
              "type": "checklist",
              "content": {
                "items": [
                  { "text": "Revisar nivel de aceite", "checked": true },
                  { "text": "Verificar presión de neumáticos", "checked": false }
                ]
              }
            },
            {
              "id": "cycle-1620000000003",
              "type": "cycle",
              "content": {
                "label": "Cambio de Filtros",
                "hasReminder": true,
                "reminderThreshold": 200,
                "items": [
                  { "name": "Kilometraje", "current": 4800, "target": 5000, "unit": "km" }
                ]
              }
            }
          ]
        },
        "style": { "width": "100%", "height": "auto" }
      }
    ]
  }
}
```

> [!NOTE]
> La recursividad se logra porque el objeto `content` de un bloque de tipo `frame` contiene a su vez un arreglo `blocks`, el cual puede contener infinitos niveles de anidación siguiendo la misma estructura.
