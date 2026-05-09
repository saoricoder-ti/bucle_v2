<template>
  <aside class="w-full bg-white flex flex-col h-screen p-6 border-r border-gray-100 transition-colors duration-500 relative">
    <!-- Branding -->
    <div class="mb-10 flex items-center justify-between cursor-pointer group" @click="store.resetToWelcome()">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <div class="w-6 h-6 bg-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="pi pi-sync text-white text-[10px] animate-spin-slow"></i>
          </div>
          <h1 class="text-2xl font-black tracking-tighter text-slate-900 group-hover:text-indigo-600 transition-colors">BUCLE</h1>
        </div>
        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-8 group-hover:text-indigo-400 transition-colors">Dashboard</p>
      </div>
    </div>

    <!-- Navegación de Categorías -->
    <nav class="flex-1 overflow-y-auto space-y-1 mt-4 pr-2 custom-scrollbar">
      <div v-if="store.loading && store.categories.length === 0" class="space-y-2 px-2">
        <div v-for="i in 5" :key="i" class="w-full h-10 bg-slate-50 animate-pulse rounded-xl"></div>
      </div>

      <template v-else-if="store.categories.length > 0">
        <div 
          v-for="cat in store.categories" :key="cat.id"
          class="relative group/item mb-0.5"
        >
          <!-- Botón de Categoría Dinámico -->
          <button 
            @click="store.selectCategory(cat)"
            class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group"
            :class="store.activeCategory?.id === cat.id 
              ? 'text-white shadow-lg' 
              : 'hover:bg-slate-50 text-slate-500 hover:text-slate-900'"
            :style="store.activeCategory?.id === cat.id ? { 
              backgroundColor: cat.color || '#6366f1',
              boxShadow: `0 10px 20px -5px ${cat.color || '#6366f1'}44`
            } : {}"
          >
            <div class="flex items-center gap-2.5 flex-1 min-w-0">
              <span class="text-base transition-transform group-hover:scale-110">
                {{ cat.emoji || '📁' }}
              </span>
              
              <!-- MODO EDICIÓN INLINE -->
              <input 
                v-if="store.categoryEditingId === cat.id"
                v-model="cat.nombre"
                @blur="store.renameCategory(cat.id, cat.nombre)"
                @keyup.enter="store.renameCategory(cat.id, cat.nombre)"
                class="bg-white text-slate-900 text-xs font-bold border-none ring-2 ring-indigo-500 rounded px-1 w-full focus:ring-indigo-500"
                auto-focus
                @click.stop
              />
              <span v-else class="font-bold text-xs tracking-tight truncate">{{ cat.nombre }}</span>
            </div>
            
            <!-- Icono de Tuerca (Superpuesto con efecto POP) -->
            <div 
              @click.stop="toggleMenu($event, cat.id)"
              class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover/item:opacity-100 p-2 bg-white rounded-xl shadow-xl transition-all duration-300 hover:scale-125 hover:rotate-90 z-20 cursor-pointer flex items-center justify-center border border-gray-100"
              :style="{ color: store.activeCategory?.id === cat.id ? (cat.color || '#6366f1') : '#475569' }"
            >
              <i class="pi pi-cog text-xs"></i>
            </div>
          </button>
        </div>

        <!-- DROPDOWN DE ACCIONES (FIXED: Siempre delante de todo) -->
        <Teleport to="body">
          <transition name="fade-slide">
            <div 
              v-if="openMenuId"
              :style="{ top: menuPos.y + 'px', left: menuPos.x + 'px' }"
              class="fixed w-44 bg-white border border-gray-100 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.2)] z-[999] p-2 animate-modal-enter"
            >
              <div class="px-3 py-2 border-b border-gray-50 mb-1">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Gestión de Bucle</p>
              </div>
              
              <button @click="startRename" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-50 flex items-center gap-3 group">
                <i class="pi pi-pencil text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
                <span class="text-[10px] font-bold text-slate-600">Renombrar</span>
              </button>
              
              <button @click="store.duplicateCategory(openMenuId); openMenuId = null" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-50 flex items-center gap-3 group">
                <i class="pi pi-copy text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
                <span class="text-[10px] font-bold text-slate-600">Duplicar</span>
              </button>

              <button @click="openPersonalize" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-50 flex items-center gap-3 group">
                <i class="pi pi-palette text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
                <span class="text-[10px] font-bold text-slate-600">Personalizar</span>
              </button>

              <div class="h-px bg-gray-50 my-1"></div>

              <button @click="store.deleteCategory(openMenuId); openMenuId = null" class="w-full text-left px-3 py-2 rounded-lg hover:bg-red-50 flex items-center gap-3 group">
                <i class="pi pi-trash text-[10px] text-slate-400 group-hover:text-red-500"></i>
                <span class="text-[10px] font-bold text-slate-600 group-hover:text-red-600">Eliminar</span>
              </button>
            </div>
          </transition>
        </Teleport>
      </template>

      <!-- Empty State -->
      <div v-else class="flex flex-col items-center justify-center h-32 text-center px-4">
        <i class="pi pi-folder-open text-slate-200 text-2xl mb-2"></i>
        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Sin categorías</p>
      </div>
    </nav>

    <!-- Acción: Nueva Categoría -->
    <div class="mt-auto pt-8 border-t border-slate-50">
      <button 
        @click="addNewCategory" 
        class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white p-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-black shadow-xl transition-all hover:scale-[1.02] active:scale-95"
      >
        <i class="pi pi-plus-circle"></i>
        <span>Agregar Categoría</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';

const store = useCategoryStore();
const openMenuId = ref(null);
const menuPos = ref({ x: 0, y: 0 });

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
  if (!e.target.closest('.fixed') && !e.target.closest('.pi-cog')) {
    openMenuId.value = null;
  }
};

onMounted(() => window.addEventListener('click', handleClickOutside));
onUnmounted(() => window.removeEventListener('click', handleClickOutside));
</script>