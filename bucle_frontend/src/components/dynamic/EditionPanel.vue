<template>
  <div v-if="store.activeSub" class="flex flex-col h-full animate-slide-in">
    <h3 class="text-[10px] font-black uppercase text-gray-400 dark:text-slate-500 tracking-[0.2em] mb-8">Panel de Edición</h3>

    <!-- Sección 1: Propiedades Base -->
    <section class="space-y-6 mb-10">
      <div class="group">
        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-focus-within:text-indigo-500 transition-colors">Nombre del Evento</label>
        <input 
          v-model="store.activeSub.nombre" 
          class="w-full mt-1.5 p-3 bg-slate-50 dark:bg-slate-800 dark:text-white rounded-2xl text-sm border-none focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-500/20 transition-all font-medium" 
          placeholder="Ej. Mantenimiento Preventivo"
        />
      </div>
      
      <div>
        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Identidad / Color</label>
        <div class="flex gap-3 mt-2">
          <div class="w-8 h-8 rounded-full bg-indigo-500 cursor-pointer border-4 border-white dark:border-slate-900 shadow-sm ring-1 ring-slate-100 dark:ring-slate-800"></div>
          <div class="w-8 h-8 rounded-full bg-emerald-500 cursor-pointer border-4 border-white dark:border-slate-900 hover:shadow-md transition-all"></div>
          <div class="w-8 h-8 rounded-full bg-amber-500 cursor-pointer border-4 border-white dark:border-slate-900 hover:shadow-md transition-all"></div>
          <div class="w-8 h-8 rounded-full bg-rose-500 cursor-pointer border-4 border-white dark:border-slate-900 hover:shadow-md transition-all"></div>
        </div>
      </div>
    </section>

    <!-- Sección 2: Herramientas Rápidas -->
    <section class="mb-10">
      <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-4">Herramientas Rápidas</p>
      <div class="grid grid-cols-2 gap-3">
        <button 
          v-for="f in funcs" :key="f.label" 
          @click="store.addBlock(f.type)"
          class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/40 border border-transparent hover:border-indigo-100 dark:hover:border-indigo-500/30 hover:bg-white dark:hover:bg-slate-800 hover:shadow-md transition-all group"
        >
          <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-700 flex items-center justify-center mb-2 shadow-sm group-hover:bg-indigo-500 group-hover:text-white transition-all">
            <i :class="['pi text-xs dark:text-slate-300', f.icon, 'group-hover:text-white']"></i>
          </div>
          <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 group-hover:text-slate-700 dark:group-hover:text-slate-300 uppercase tracking-widest transition-colors">{{ f.label }}</span>
        </button>
      </div>
    </section>

    <!-- Sección 3: Importaciones y Herramientas -->
    <section class="mt-auto pt-6 border-t border-slate-100 dark:border-slate-800 space-y-3">
      <button 
        @click="store.importFile('pdf')"
        class="w-full py-4 px-6 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 dark:shadow-none flex items-center justify-center gap-3 active:scale-95"
      >
        <i class="pi pi-cloud-upload"></i>
        <span>Importar PDF / CSV</span>
      </button>
      
      <button class="w-full py-4 px-4 bg-slate-900 dark:bg-white dark:text-slate-900 text-white rounded-2xl text-[10px] font-black uppercase hover:bg-black dark:hover:bg-slate-100 hover:shadow-lg transition-all flex items-center justify-center gap-3">
        <i class="pi pi-plus-circle"></i> 
        <span>Crear Formulario</span>
      </button>
    </section>

    <!-- Overlay de Importación -->
    <ImportLoader :active="store.isImporting" />
  </div>
  
  <!-- Empty State del Panel -->
  <div v-else class="h-full flex flex-col items-center justify-center text-center px-4">
    <div class="w-16 h-16 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
      <i class="pi pi-sliders-h text-gray-200 dark:text-slate-800 text-xl"></i>
    </div>
    <p class="text-[10px] font-bold text-gray-300 dark:text-slate-700 uppercase tracking-[0.2em] max-w-[150px]">
      Selecciona un evento para configurar propiedades
    </p>
  </div>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';
import ImportLoader from './ImportLoader.vue';

const store = useCategoryStore();
const funcs = [
  { label: 'Texto', type: 'text', icon: 'pi-align-left' },
  { label: 'Tabla', type: 'table', icon: 'pi-table' },
  { label: 'Mapa', type: 'map', icon: 'pi-map' },
  { label: 'Imagen', type: 'image', icon: 'pi-image' },
  { label: 'Checklist', type: 'checklist', icon: 'pi-check-square' },
  { label: 'Código', type: 'code', icon: 'pi-code' }
];
</script>
