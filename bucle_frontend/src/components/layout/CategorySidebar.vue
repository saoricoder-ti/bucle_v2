<template>
  <aside class="w-full bg-white flex flex-col h-screen p-8 border-r border-gray-100 transition-colors duration-500 relative">
    
    <!-- MÓDULO DE NAVEGACIÓN PRINCIPAL -->
    <nav class="flex-1 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
      
      <!-- Iteración de Menú Estático + Proyectos -->
      <div v-for="item in menuItems" :key="item.label">
        
        <!-- Ítem Estándar -->
        <button 
          v-if="!item.isProyectos"
          class="w-full flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-200 group text-slate-500 hover:bg-slate-50 hover:text-slate-900"
          :class="{ 'text-indigo-600 font-bold bg-slate-50/50': item.active }"
        >
          <i :class="[item.icon, 'text-sm transition-colors', item.active ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600']"></i>
          <span class="text-xs font-medium tracking-tight truncate">{{ item.label }}</span>
        </button>

        <!-- Ítem Especial: Proyectos (Acordeón / Dropdown) -->
        <div v-else class="space-y-1">
          <button 
            @click="isProyectosOpen = !isProyectosOpen"
            class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-slate-500 hover:bg-slate-50 hover:text-slate-900"
          >
            <div class="flex items-center gap-3 flex-1 min-w-0">
              <i :class="[item.icon, 'text-sm text-slate-400 group-hover:text-slate-600']"></i>
              <span class="text-xs font-medium tracking-tight truncate">{{ item.label }}</span>
            </div>
            <i class="pi pi-chevron-down text-[10px] transition-transform duration-300" :class="{ 'rotate-180': isProyectosOpen }"></i>
          </button>

          <!-- Listado de Categorías (Hijo de Proyectos) -->
          <div v-if="isProyectosOpen" class="ml-4 pl-2 border-l border-gray-100 space-y-0.5 mt-1">
            
            <!-- Loading State -->
            <div v-if="store.loading && store.categories.length === 0" class="space-y-1">
              <div v-for="i in 3" :key="i" class="w-full h-8 bg-slate-50 animate-pulse rounded-lg"></div>
            </div>

            <!-- Lista de Categorías (Top 3 o Full) -->
            <template v-else-if="store.categories.length > 0">
              <div 
                v-for="cat in displayedCategories" :key="cat.id"
                class="relative group/item mb-0.5"
              >
                <button 
                  @click="store.selectCategory(cat)"
                  class="w-full flex items-center justify-between px-2 py-1.5 rounded-lg transition-all duration-200 group"
                  :class="store.activeCategory?.id === cat.id 
                    ? 'bg-slate-50/80 text-slate-900' 
                    : 'hover:bg-slate-50/50 text-slate-500 hover:text-slate-900'"
                >
                  <div class="flex items-center gap-2 flex-1 min-w-0">
                    <!-- Icono minimalista que cambia de color al seleccionar -->
                    <i class="pi pi-folder text-[11px]" 
                       :style="{ color: store.activeCategory?.id === cat.id ? (cat.color || '#6366f1') : '#94a3b8' }"></i>
                    
                    <!-- MODO EDICIÓN INLINE -->
                    <input 
                      v-if="store.categoryEditingId === cat.id"
                      v-model="cat.nombre"
                      @blur="store.renameCategory(cat.id, cat.nombre)"
                      @keyup.enter="store.renameCategory(cat.id, cat.nombre)"
                      class="bg-white text-slate-900 text-[11px] font-bold border-none ring-1 ring-indigo-500 rounded px-1 w-full focus:ring-indigo-500"
                      auto-focus
                      @click.stop
                    />
                    <span v-else class="font-medium text-[11px] tracking-tight truncate">{{ cat.nombre }}</span>
                  </div>
                  
                  <!-- Menú contextual -->
                  <div 
                    @click.stop="toggleMenu($event, cat.id)"
                    class="opacity-0 group-hover/item:opacity-100 p-1 bg-white rounded-md shadow-sm transition-all duration-200 hover:scale-110 z-20 cursor-pointer flex items-center justify-center border border-gray-100"
                  >
                    <i class="pi pi-ellipsis-h text-[10px] text-slate-400 hover:text-slate-600"></i>
                  </div>
                </button>
              </div>

              <!-- Botón Ver Todos / Mostrar Menos -->
              <button 
                v-if="store.categories.length > 3"
                @click="showAllCategories = !showAllCategories"
                class="w-full text-left px-2 py-1.5 text-[10px] font-bold text-slate-400 hover:text-indigo-600 transition-colors"
              >
                {{ showAllCategories ? 'Mostrar menos' : `Ver todos (${store.categories.length})` }}
              </button>
            </template>

            <!-- Empty State -->
            <div v-else class="text-[10px] text-slate-400 py-2 pl-2">Sin proyectos</div>
          </div>
        </div>
      </div>
    </nav>

    <!-- COMPONENTE DE USUARIO (Espacio para el perfil) -->
    <div class="border-t border-gray-100 pt-4 mt-auto">
      <div class="flex items-center gap-3 px-2 py-3 bg-slate-50/50 rounded-2xl border border-gray-50 hover:bg-slate-50 transition-colors cursor-pointer">
        <!-- Avatar minimalista -->
        <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl flex items-center justify-center border border-indigo-200/50 shadow-sm shrink-0">
          <span class="text-xs font-bold text-indigo-600">BR</span>
        </div>
        <!-- Datos de usuario -->
        <div class="min-w-0">
          <p class="text-xs font-bold text-slate-800 truncate">Betty Rodriguez</p>
          <p class="text-[9px] font-medium text-slate-400 uppercase tracking-wider truncate">Admin Space</p>
        </div>
      </div>
    </div>

    <!-- Acción: Nuevo Proyecto (Antes Agregar Categoría) -->
    <div class="mt-4">
      <button 
        @click="addNewCategory" 
        class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white p-3 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-black shadow-xl transition-all hover:scale-[1.02] active:scale-95"
      >
        <i class="pi pi-plus-circle text-xs"></i>
        <span>Nuevo Proyecto</span>
      </button>
    </div>

    <!-- DROPDOWN DE ACCIONES TELEPORTADO -->
    <Teleport to="body">
      <transition name="fade-slide">
        <div 
          v-if="openMenuId"
          :style="{ top: menuPos.y + 'px', left: menuPos.x + 'px' }"
          class="fixed w-40 bg-white border border-gray-100 rounded-xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] z-[999] p-1.5 animate-modal-enter"
        >
          <button @click="startRename" class="w-full text-left px-2 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 group">
            <i class="pi pi-pencil text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
            <span class="text-[10px] font-bold text-slate-600">Renombrar</span>
          </button>
          
          <button @click="store.duplicateCategory(openMenuId); openMenuId = null" class="w-full text-left px-2 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 group">
            <i class="pi pi-copy text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
            <span class="text-[10px] font-bold text-slate-600">Duplicar</span>
          </button>

          <button @click="openPersonalize" class="w-full text-left px-2 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-2 group">
            <i class="pi pi-palette text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
            <span class="text-[10px] font-bold text-slate-600">Personalizar</span>
          </button>

          <div class="h-px bg-gray-50 my-1"></div>

          <button @click="store.deleteCategory(openMenuId); openMenuId = null" class="w-full text-left px-2 py-1.5 rounded-lg hover:bg-red-50 flex items-center gap-2 group">
            <i class="pi pi-trash text-[10px] text-slate-400 group-hover:text-red-500"></i>
            <span class="text-[10px] font-bold text-slate-600 group-hover:text-red-600">Eliminar</span>
          </button>
        </div>
      </transition>
    </Teleport>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';

const store = useCategoryStore();
const openMenuId = ref(null);
const menuPos = ref({ x: 0, y: 0 });

// Estados de UI para el colapso
const isProyectosOpen = ref(true);
const showAllCategories = ref(false);

// Menú de Navegación Completo según especificación
const menuItems = computed(() => [
  { label: 'Workspace', icon: 'pi pi-grid', active: store.view === 'welcome' || !store.activeCategory },
  { label: 'Proyectos', icon: 'pi pi-folder', isProyectos: true },
  { label: 'Tareas', icon: 'pi pi-check-square' },
  { label: 'Habitos', icon: 'pi pi-heart' },
  { label: 'Routinas', icon: 'pi pi-sync' },
  { label: 'Analiticas', icon: 'pi pi-chart-bar' },
  { label: 'Configuracion', icon: 'pi pi-cog' },
]);

// Lógica de filtrado: Top 3 o Todas
const displayedCategories = computed(() => {
  if (showAllCategories.value) {
    return store.categories;
  }
  // En un caso real, ordenaríamos por 'visitedCount' o 'updatedAt'
  // Por ahora tomamos las 3 primeras como las "más usadas"
  return store.categories.slice(0, 3);
});

const toggleMenu = (event, id) => {
  if (openMenuId.value === id) {
    openMenuId.value = null;
    return;
  }
  
  const rect = event.currentTarget.getBoundingClientRect();
  menuPos.value = {
    x: rect.right + 10,
    y: rect.top
  };
  
  openMenuId.value = id;
};

const startRename = () => {
  store.categoryEditingId = openMenuId.value;
  openMenuId.value = null;
};

const openPersonalize = () => {
  const cat = store.categories.find(c => c.id === openMenuId.value);
  if (cat) {
    store.activeCategory = cat;
    store.categoryFormMode = 'edit';
    store.isCreatingCategory = true;
  }
  openMenuId.value = null;
};

const addNewCategory = () => {
  store.categoryFormMode = 'create';
  store.triggerCategoryModal();
};

const handleClickOutside = (e) => {
  if (!e.target.closest('.fixed') && !e.target.closest('.pi-ellipsis-h')) {
    openMenuId.value = null;
  }
};

onMounted(() => window.addEventListener('click', handleClickOutside));
onUnmounted(() => window.removeEventListener('click', handleClickOutside));
</script>