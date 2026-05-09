import { defineStore } from 'pinia';
import { categoriasApi } from '@/api/endpoints/categorias';
import router from '@/router';

export const useCategoryStore = defineStore('category', {
  /**
   * Estado Global de la Aplicación
   */
  state: () => ({
    categories: [],      // Banner izquierdo (Categorías principales)
    subcategories: [],   // Dashboard central (Lista de eventos/subcategorías)
    activeCategory: null,// Categoría seleccionada
    activeSub: null,     // Subcategoría/Evento en edición activa
    view: 'dashboard',   // Control de vista: 'dashboard' o 'editor'
    loading: false,      // Estado de carga global
    error: null,         // Manejo de errores
    isPreviewMode: false,// Modo vista previa del editor
    schema: null,        // Esquema dinámico del backend
    showSlashMenu: false,// Control del menú de comandos "/"
    isImporting: false,  // Estado de importación de archivos
    toast: { show: false, message: '' }, // Notificaciones flotantes
    isCreatingCategory: false,           // Control de visibilidad del formulario
    categoryFormMode: 'create',          // 'create' o 'edit'
    categoryEditingId: null,             // ID de la categoría que se está renombrando inline
    subEditingId: null,                  // ID de la subcategoría que se está renombrando inline
    isEditionPanelOpen: true,            // Control del panel de edición
  }),

  /**
   * Actions: Lógica de negocio y persistencia
   */
  actions: {
    /**
     * Muestra una notificación de éxito temporal
     */
    showSuccess(msg) {
      this.toast = { show: true, message: msg };
      setTimeout(() => { this.toast.show = false; }, 4000);
    },

    /**
     * Alterna la visibilidad del panel de edición
     */
    toggleEditionPanel() {
      this.isEditionPanelOpen = !this.isEditionPanelOpen;
    },

    /**
     * Importación de archivos (Simulación y procesamiento)
     */
    async importFile(fileType) {
      this.isImporting = true;
      try {
        // Simulamos la latencia de procesamiento o scrapers
        await new Promise(resolve => setTimeout(resolve, 3000));
        
        if (fileType === 'pdf') {
          this.showSuccess('Datos de PDF extraídos correctamente');
        } else {
          this.showSuccess('CSV importado al Bucle con éxito');
        }
      } finally {
        this.isImporting = false;
      }
    },
    
    /**
     * 1.4 Dispara el modal de creación de categoría
     */
    triggerCategoryModal() {
      this.isCreatingCategory = true;
    },
    
    /**
     * 1.5 Registra una nueva categoría genérica en el sistema
     */
    async addCategory(data) {
      try {
        if (this.categoryFormMode === 'edit' && this.activeCategory) {
          await categoriasApi.updateCategory(this.activeCategory.id, data);
          this.showSuccess('Bucle actualizado');
        } else {
          await categoriasApi.registrarCategoria(data);
          this.showSuccess('Bucle creado exitosamente');
        }
        await this.initApp(); // Refresca el sidebar
        this.isCreatingCategory = false;
      } catch (err) {
        console.error("Error al procesar categoría:", err);
      }
    },

    /**
     * 1.6 Elimina una categoría
     */
    async deleteCategory(id) {
      if (!confirm('¿Estás seguro de eliminar este Bucle? Se borrarán todos sus eventos.')) return;
      try {
        await categoriasApi.deleteCategory(id);
        this.activeCategory = null;
        await this.initApp();
        this.showSuccess('Bucle eliminado');
      } catch (err) {
        console.error("Error al eliminar:", err);
      }
    },

    /**
     * 1.7 Duplica una categoría
     */
    async duplicateCategory(id) {
      try {
        await categoriasApi.duplicateCategory(id);
        await this.initApp();
        this.showSuccess('Bucle duplicado correctamente');
      } catch (err) {
        console.error("Error al duplicar:", err);
      }
    },

    /**
     * 1.8 Renombra una categoría (Inline)
     */
    async renameCategory(id, newName) {
      try {
        await categoriasApi.updateCategory(id, { nombre: newName });
        const cat = this.categories.find(c => c.id === id);
        if (cat) cat.nombre = newName;
        this.categoryEditingId = null;
        this.showSuccess('Nombre actualizado');
      } catch (err) {
        console.error("Error al renombrar:", err);
      }
    },

    /**
     * 1. Inicializa la aplicación cargando las categorías principales
     */
    async initApp() {
      this.loading = true;
      try {
        const res = await categoriasApi.fetchCategories();
        this.categories = res.data;
      } catch (err) {
        this.error = "Error al conectar con el servidor";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    /**
     * 2. Cambia de categoría y carga sus subcategorías filtradas
     */
    async selectCategory(cat) {
      this.activeCategory = cat;
      this.view = 'dashboard';
      this.activeSub = null;
      this.loading = true;
      try {
        const res = await categoriasApi.fetchSubcategories(cat.id);
        this.subcategories = res.data;
        
        // También intentamos cargar el esquema de la categoría
        const schemaRes = await categoriasApi.getSchema(cat.id);
        this.schema = schemaRes.data;
      } catch (err) {
        console.warn(`No se pudo cargar el esquema para ${cat.nombre}, usando genérico.`);
        this.schema = null;
      } finally {
        this.loading = false;
        router.push({ name: 'workspace', params: { categoryName: cat.nombre.toLowerCase() } });
      }
    },

    /**
     * 3. Sincroniza los bloques y metadatos con la DB (Auto-guardado)
     */
    async saveActiveSub() {
      if (!this.activeSub) return;
      
      try {
        await categoriasApi.updateSubcategory(this.activeSub.id, {
          nombre: this.activeSub.nombre,
          emoji: this.activeSub.emoji,
          color: this.activeSub.color,
          descripcion: this.activeSub.descripcion,
          blocks: this.activeSub.blocks // El backend se encarga del json_encode
        });
      } catch (err) {
        console.error("Error en el auto-guardado:", err);
      }
    },

    /**
     * Abre el editor para una subcategoría específica
     */
    openEditor(sub) {
      // 3. Sincronización de Referencias: apuntamos al objeto directo de la lista
      this.activeSub = this.subcategories.find(s => s.id === sub.id) || sub;
      this.view = 'editor';
      
      // Inicializamos bloques si están vacíos
      if (!this.activeSub.blocks || this.activeSub.blocks.length === 0) {
        this.activeSub.blocks = [
          // Bloque de título (oculto en el canvas, se usa para el breadcrumb/H1)
          { id: 'title-' + Date.now(), type: 'text', content: sub.nombre, style: 'h1', role: 'main-title' },
          // Primer bloque de texto real para que el usuario empiece a escribir
          { id: 'start-' + Date.now(), type: 'text', content: '', style: 'p' }
        ];
      }
      
      router.push({ 
        name: 'workspace', 
        params: { 
          categoryName: this.activeCategory.nombre.toLowerCase(), 
          subCategoryName: sub.nombre.toLowerCase() 
        } 
      });
    },

    /**
     * Actualiza el contenido de un bloque específico por ID
     */
    updateBlockData(blockId, newContent) {
      if (!this.activeSub) return;
      const block = this.activeSub.blocks.find(b => b.id === blockId);
      if (block) {
        block.content = newContent;
      }
    },

    /**
     * Añade un nuevo bloque dinámico al editor
     */
    addBlock(type) {
      if (!this.activeSub) return;

      const newBlock = {
        id: Date.now(),
        type: type,
        content: type === 'reminder' ? {
          label: 'Nuevo Recordatorio',
          date: new Date().toISOString().split('T')[0],
          time: '12:00',
          frequency: 'once',
          isActive: true
        } : type === 'cycle' ? { 
          label: 'Nuevo Servicio Multi-medida', 
          hasReminder: false,
          reminderThreshold: 500,
          reminderUnit: 'km',
          items: [
            { name: 'Métrica General', current: 0, target: 10000, unit: 'km' }
          ]
        } : 
                 type === 'map' ? { lat: -0.1807, lng: -78.4678 } : 
                 type === 'table' ? { columns: ['Col 1', 'Col 2'], rows: [] } :
                 type === 'calendar' ? { selectedDate: new Date().toISOString(), events: [] } :
                 type === 'list' ? { items: [''] } : '',
        style: type === 'text' ? 'p' : null
      };

      this.activeSub.blocks.push(newBlock);
      this.showSlashMenu = false;
      
      // Disparamos guardado automático al añadir bloque
      this.saveActiveSub();
    },

    /**
     * 1.6 Crea una nueva subcategoría real en la DB vinculada a la activa
     */
    async createNewSub() {
      if (!this.activeCategory) return;
      
      this.loading = true;
      try {
        const payload = {
          categoria_id: this.activeCategory.id,
          nombre: 'Nuevo Evento',
          emoji: '✨',
          color: '#6366f1', // Color inicial premium
          descripcion: 'Haz clic para editar...',
          blocks: [
            // Bloque de título (oculto en el canvas, se usa para el breadcrumb/H1)
            { id: 'title-' + Date.now(), type: 'text', content: 'Nuevo Evento', style: 'h1', role: 'main-title' },
            // Primer bloque de texto real para que el usuario empiece a escribir
            { id: 'start-' + Date.now(), type: 'text', content: '', style: 'p' }
          ] 
        };

        const res = await categoriasApi.registrarSubcategoria(payload);
        
        if (res.data.status === 'success') {
          // Construimos el objeto local con el ID real devuelto por la DB
          const createdSub = {
            id: res.data.id,
            ...payload
          };

          this.subcategories.unshift(createdSub);
          this.openEditor(createdSub);
          this.showSuccess('Nuevo evento activado');
        }
      } catch (err) {
        console.error("Error al crear subcategoría:", err);
        this.error = "No se pudo crear el evento";
      } finally {
        this.loading = false;
      }
    },

    /**
     * Regresa al dashboard principal de forma fluida
     */
    async goBack() {
      this.activeSub = null;
      if (this.activeCategory) {
        await this.selectCategory(this.activeCategory);
      } else {
        this.view = 'dashboard';
        router.push('/workspace');
      }
    },

    /**
     * Vuelve a la pantalla de bienvenida
     */
    resetToWelcome() {
      this.activeCategory = null;
      this.activeSub = null;
      this.view = 'dashboard';
      router.push('/workspace');
    },

    /**
     * 1.9 Renombra una subcategoría (Inline)
     */
    async renameSubcategory(id, newName) {
      try {
        await categoriasApi.updateSubcategory(id, { nombre: newName });
        const sub = this.subcategories.find(s => s.id === id);
        if (sub) sub.nombre = newName;
        this.subEditingId = null;
        this.showSuccess('Evento renombrado');
      } catch (err) {
        console.error("Error al renombrar subcategoría:", err);
      }
    },

    /**
     * 2.0 Elimina una subcategoría
     */
    async deleteSubcategory(id) {
      if (!confirm('¿Eliminar este evento de forma permanente?')) return;
      try {
        await categoriasApi.deleteSubcategory(id);
        this.subcategories = this.subcategories.filter(s => s.id !== id);
        this.showSuccess('Evento eliminado');
      } catch (err) {
        console.error("Error al eliminar subcategoría:", err);
      }
    },

    /**
     * 2.1 Duplica una subcategoría
     */
    async duplicateSubcategory(id) {
      try {
        const res = await categoriasApi.duplicateSubcategory(id);
        if (res.data.status === 'success') {
          const subRes = await categoriasApi.fetchSubcategories(this.activeCategory.id);
          this.subcategories = subRes.data;
          this.showSuccess('Evento duplicado');
        }
      } catch (err) {
        console.error("Error al duplicar subcategoría:", err);
      }
    }
  }
});