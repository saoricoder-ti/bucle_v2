<template>
  <aside class="w-full bg-white dark:bg-slate-950 flex flex-col h-screen p-6 border-r border-gray-100 dark:border-slate-800 transition-colors duration-500">
    <!-- Branding -->
    <div class="mb-10 flex items-center justify-between">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <div class="w-6 h-6 bg-indigo-600 rounded-lg flex items-center justify-center">
            <i class="pi pi-sync text-white text-[10px] animate-spin-slow"></i>
          </div>
          <h1 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white">BUCLE</h1>
        </div>
        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-8">Workspace</p>
      </div>
      
      <ThemeToggle />
    </div>

    <!-- Navegación de Categorías -->
    <nav class="flex-1 overflow-y-auto space-y-1 mt-4 pr-2 custom-scrollbar">
      <!-- Estado de Carga -->
      <div v-if="store.loading && store.categories.length === 0" class="space-y-2 px-2">
        <div v-for="i in 5" :key="i" class="w-full h-10 bg-slate-50 dark:bg-slate-900 animate-pulse rounded-xl"></div>
      </div>

      <!-- Lista de Categorías -->
      <template v-else-if="store.categories.length > 0">
        <button 
          v-for="cat in store.categories" :key="cat.id"
          @click="store.selectCategory(cat)"
          class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group mb-0.5"
          :class="store.activeCategory?.id === cat.id 
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' 
            : 'hover:bg-slate-50 dark:hover:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100'"
        >
          <div class="flex items-center gap-2.5">
            <span class="text-base transition-transform group-hover:scale-110">
              {{ cat.emoji || '📁' }}
            </span>
            <span class="font-bold text-xs tracking-tight">{{ cat.nombre }}</span>
          </div>
          
          <i 
            v-if="store.activeCategory?.id === cat.id" 
            class="pi pi-chevron-right text-[8px] animate-pulse"
          ></i>
        </button>
      </template>

      <!-- Empty State -->
      <div v-else class="flex flex-col items-center justify-center h-32 text-center px-4">
        <i class="pi pi-folder-open text-slate-200 dark:text-slate-800 text-2xl mb-2"></i>
        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Sin categorías</p>
      </div>
    </nav>

    <!-- Acción: Nueva Categoría -->
    <div class="mt-auto pt-8 border-t border-slate-50 dark:border-slate-900">
      <button 
        @click="addNewCategory" 
        class="w-full flex items-center justify-center gap-2 bg-slate-900 dark:bg-indigo-600 text-white p-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-black dark:hover:bg-indigo-700 shadow-xl dark:shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-95"
      >
        <i class="pi pi-plus-circle"></i>
        <span>Agregar Categoría</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';
import ThemeToggle from '@/components/common/ThemeToggle.vue';

const store = useCategoryStore();

const addNewCategory = () => {
  // Disparamos el modal de creación definido en el store
  store.triggerCategoryModal();
};
</script>