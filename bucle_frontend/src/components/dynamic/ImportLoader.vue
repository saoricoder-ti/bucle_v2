<template>
  <div v-if="active" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md transition-all duration-500">
    <div class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] shadow-2xl flex flex-col items-center max-w-sm w-full mx-4 border border-slate-100 dark:border-slate-800">
      
      <!-- Animación de Escaneo -->
      <div class="relative w-24 h-24 mb-8">
        <div class="absolute inset-0 border-4 border-indigo-100 dark:border-slate-800 rounded-2xl"></div>
        <div class="absolute inset-0 border-4 border-indigo-500 rounded-2xl animate-pulse"></div>
        <!-- Línea de escaneo láser -->
        <div class="absolute left-0 right-0 h-1 bg-indigo-400 shadow-[0_0_15px_#818cf8] animate-scan top-0"></div>
        <i class="pi pi-file-import absolute inset-0 flex items-center justify-center text-3xl text-indigo-500"></i>
      </div>

      <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-tighter">Procesando Archivo</h3>
      <p class="text-xs text-slate-400 dark:text-slate-500 text-center font-medium leading-relaxed">
        Estamos extrayendo y vinculando los datos a tu Bucle de {{ store.activeCategory?.nombre }}...
      </p>

      <!-- Barra de progreso discreta -->
      <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full mt-8 overflow-hidden">
        <div class="h-full bg-indigo-500 animate-progress"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';
const store = useCategoryStore();
defineProps({ active: Boolean });
</script>
