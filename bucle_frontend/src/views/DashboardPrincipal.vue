<template>
  <div class="flex-1 transparent-canvas p-12 overflow-y-auto transition-colors duration-500 custom-scrollbar">
    <!-- Encabezado dinámico -->
    <header class="mb-10">
      <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Dashboard Principal</h2>
      <h1 class="text-4xl font-black text-slate-900">{{ store.activeCategory?.nombre || 'Selecciona una Categoría' }}</h1>
    </header>

    <!-- Grid de Subcategorías -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- Estado de Carga (Esqueletos) -->
      <template v-if="store.loading && store.subcategories.length === 0">
        <div v-for="i in 3" :key="i" class="h-48 bg-slate-50 animate-pulse rounded-[2.5rem] border border-slate-100"></div>
      </template>

      <!-- Lista de Tarjetas -->
      <template v-else>
        <SubcategoryCard 
          v-for="sub in store.subcategories" 
          :key="sub.id" 
          :data="sub"
          @click="store.openEditor(sub)" 
        />
      </template>
      
      <!-- Botón para crear nueva subcategoría -->
      <button 
        @click="store.createNewSub" 
        class="border-2 border-dashed border-gray-100 rounded-[2.5rem] p-8 flex flex-col items-center justify-center gap-3 hover:border-indigo-300 hover:bg-indigo-50/50 transition-all group min-h-[12rem]"
      >
        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-white transition-colors shadow-sm">
          <i class="pi pi-plus text-slate-400 group-hover:text-indigo-500"></i>
        </div>
        <span class="text-xs font-bold text-slate-400 group-hover:text-indigo-600 uppercase tracking-tighter">Añadir Evento</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';
import SubcategoryCard from '@/components/dynamic/SubcategoryCard.vue';

const store = useCategoryStore();
</script>