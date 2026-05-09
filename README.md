# Bucle Workspace

**Bucle Workspace** es un ecosistema de trabajo inteligente, altamente escalable y dinámico, diseñado específicamente para la gestión estructurada de eventos y servicios. A través de una interfaz moderna y fluida, Bucle proporciona herramientas avanzadas de organización de datos, enfocándose en la productividad y en una experiencia de usuario (UX) excepcional.

---

## 🏗️ Arquitectura del Proyecto

El proyecto está construido sobre un stack tecnológico moderno y robusto, garantizando rendimiento, mantenibilidad y una excelente experiencia de desarrollo:

- **Frontend Core**: **Vue 3** utilizando la **Composition API**, lo que permite una lógica de componentes más limpia, reactiva y modular.
- **Gestión de Estado**: **Pinia** actúa como la fuente de verdad para el estado global de la aplicación, manejando de forma eficiente la información de categorías, subcategorías y sincronización de datos.
- **Estilos y Diseño**: **Tailwind CSS** proporciona un sistema de utilidades CSS altamente personalizable, permitiendo la creación de interfaces responsivas y de estética premium de forma ágil.
- **Backend API REST**: **CodeIgniter 4** (PHP) expone una API RESTful sólida y segura que maneja la lógica de negocio, autenticación y persistencia de datos, conectándose a los sistemas de bases de datos subyacentes.

---

## 🧩 Sistema de Bloques Dinámicos (Core)

El corazón del editor de Bucle es su **Sistema de Bloques Dinámicos**. Este motor permite componer documentos complejos a través de componentes modulares.

### BlockRenderer y Persistencia
El motor principal utiliza un componente `BlockRenderer` que interpreta dinámicamente una estructura de datos en formato **JSON**. Cada bloque es un componente aislado que se renderiza según su `type` y actualiza su propio fragmento de datos. Todo el documento se guarda y se recupera como un único objeto JSON estructurado, facilitando la integridad estructural y simplificando la sincronización con la base de datos.

### Bloques Implementados
El ecosistema cuenta con un repertorio creciente de bloques funcionales:
- **Texto**: Cabeceras (`H1`) y Párrafos (`P`) con edición rica e intuitiva.
- **Tablas**: Estructuras de datos tabulares dinámicas para organizar métricas o listados.
- **Mapas**: Integración de geolocalización y visualización de ubicaciones clave.
- **Imágenes**: Soporte para la inserción y previsualización de contenido multimedia.
- **Checklists**: Listas de tareas interactivas con **persistencia de estado** (marca de completado) en tiempo real.
- **Calendarios**: Herramientas de programación y control de fechas críticas.
- **Listas**: Listas de viñetas para enumeraciones y organización de puntos.
- **Ciclos de Progreso (Progress Rings)**: Componentes trazadores visuales dinámicos para monitorear metas numéricas y ciclos operativos (ej. ciclos de servicio de kilometraje), sincronizados reactivamente con los datos del servidor.

---

## 💫 Experiencia de Usuario (UX)

La plataforma está obsesionada con ofrecer una experiencia de usuario que reduzca la fricción cognitiva:

- **Reordenamiento Intuitivo**: Mediante la integración de `vuedraggable`, los usuarios pueden reorganizar cualquier bloque dentro del documento simplemente arrastrando y soltando (Drag & Drop), ofreciendo una libertad estructural total.
- **Edición Inline**: Las subcategorías y los títulos de los bloques pueden editarse directamente en el lugar (inline), sin necesidad de abrir modales disruptivos.
- **Selector de Propiedades (Estilo Notion)**: Bucle implementa un sistema de menús contextuales y selectores de bloques `/` inspirados en Notion. Los usuarios pueden invocar herramientas y cambiar tipos de bloques mediante atajos de teclado o clics rápidos en la interfaz.

---

## 🧭 Navegación Dinámica

Bucle se despide de las arquitecturas de rutas rígidas y estáticas. El sistema implementa un flujo unificado y contextual:

- **Flujo Unificado**: El usuario transita naturalmente por la jerarquía: **Categoría → Dashboard → Editor** sin recargas de página ni pérdida de contexto.
- **Rutas Automáticas**: Las rutas en la aplicación (URL) se construyen y resuelven automáticamente en base al nombre del registro activo o slug, sin necesidad de declarar cada ruta manualmente en el router. Esto permite que la plataforma escale infinitamente a medida que los usuarios crean nuevos espacios de trabajo o categorías.

---

## ⚡ Gestión de Estado (Pinia)

El manejo de la información en tiempo real es gestionado por Pinia para asegurar la coherencia entre el cliente y el servidor:

- **`activeCategory` y `activeSub`**: Stores modulares mantienen el contexto exacto de dónde se encuentra el usuario, gestionando la categoría padre actual y la subcategoría/documento en edición.
- **Sincronización `saveActiveSub`**: Cualquier cambio realizado en el editor, ya sea escribir un texto o marcar un checklist, dispara la acción `saveActiveSub`. Esto sincroniza de manera optimista y en tiempo real el estado local en JSON con el backend de CodeIgniter 4, garantizando que no se pierda ningún dato y manteniendo la UI siempre responsiva.

---

## 🎨 Guía de Estilos y Estética

El diseño visual de Bucle no es un detalle menor; está pensado para ser moderno, relajante e inmersivo:

- **Variables CSS y Modo Oscuro**: A través del archivo `main.css`, se definen variables nativas de CSS para la paleta de colores (Design Tokens). Esto permite un soporte nativo, performante y absoluto para **Modo Oscuro (Dark Mode)** y Modo Claro.
- **Animaciones y Transiciones Suaves**: Todas las interacciones de UI (apertura de paneles, navegación entre páginas, renderizado de bloques) están acompañadas de transiciones calculadas (`slide-right`, `fade`, micro-interacciones), aportando una sensación "Premium" al uso diario.

---

> *Bucle Workspace: Estructura tus ideas, controla tus ciclos y sincroniza tu trabajo.*
