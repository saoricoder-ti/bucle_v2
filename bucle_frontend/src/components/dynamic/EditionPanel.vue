<template>
  <div v-if="store.activeSub" class="flex flex-col h-full animate-slide-in">
    <div class="flex items-center gap-3 mb-8">
      <button 
        @click="store.toggleEditionPanel()"
        class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 transition-all text-slate-400 hover:text-slate-700 shadow-sm"
        title="Ocultar Panel"
      >
        <i class="pi pi-arrow-right text-[10px]"></i>
      </button>
      <h3 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] m-0">Panel de Edición</h3>
    </div>
    <!-- Sección 1: Propiedades Base -->
    <section class="space-y-6 mb-10">
      <div class="group">
        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-focus-within:text-indigo-600 transition-colors">Nombre del Evento</label>
        <input 
          v-model="store.activeSub.nombre" 
          @blur="store.saveActiveSub"
          class="w-full mt-1.5 p-3 bg-slate-50 text-slate-900 rounded-2xl text-sm border-none focus:ring-2 focus:ring-indigo-100 transition-all font-medium" 
          placeholder="Ej. Mantenimiento Preventivo"
        />
      </div>
      
      <div>
        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Identidad / Color</label>
        <!-- Gama de colores expandida y responsiva -->
        <div class="grid grid-cols-6 gap-3 mt-3">
          <button 
            v-for="color in availableColors" 
            :key="color"
            @click="store.activeSub.color = color; store.saveActiveSub()"
            class="w-8 h-8 rounded-full border-4 transition-all hover:scale-110 active:scale-90 shadow-sm"
            :class="store.activeSub.color === color ? 'border-indigo-600 scale-110' : 'border-white'"
            :style="{ backgroundColor: color }"
          ></button>
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
          class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50/50 border border-transparent hover:border-indigo-100 hover:bg-white hover:shadow-md transition-all group"
        >
          <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center mb-2 shadow-sm group-hover:bg-indigo-500 group-hover:text-white transition-all">
            <i :class="['pi text-xs text-slate-400', f.icon, 'group-hover:text-white']"></i>
          </div>
          <span class="text-[9px] font-black text-slate-400 group-hover:text-slate-700 uppercase tracking-widest transition-colors">{{ f.label }}</span>
        </button>
      </div>
    </section>

    <!-- Sección 3: Importaciones y Herramientas -->
    <section class="mt-auto pt-6 border-t border-slate-100 space-y-3">
      <button 
        @click="store.importFile('pdf')"
        class="w-full py-4 px-6 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase hover:bg-indigo-700 transition-all flex items-center justify-center gap-3 active:scale-95"
      >
        <i class="pi pi-cloud-upload"></i>
        <span>Importar PDF / CSV</span>
      </button>
      
      <button class="w-full py-4 px-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase hover:bg-black hover:shadow-lg transition-all flex items-center justify-center gap-3">
        <i class="pi pi-plus-circle"></i> 
        <span>Crear Formulario</span>
      </button>
    </section>

    <!-- Overlay de Importación -->
    <ImportLoader :active="store.isImporting" />
  </div>
  
  <!-- Empty State del Panel -->
  <div v-else class="h-full flex flex-col items-center justify-center text-center px-4">
    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
      <i class="pi pi-sliders-h text-gray-200 text-xl"></i>
    </div>
    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.2em] max-w-[150px]">
      Selecciona un evento para configurar propiedades
    </p>
  </div>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';
import ImportLoader from './ImportLoader.vue';

const store = useCategoryStore();

// Gama de colores extendida (12 colores premium)
const availableColors = [
  '#6366f1', '#8b5cf6', '#d946ef', '#f43f5e', // Indigo, Violet, Fuchsia, Rose
  '#f59e0b', '#10b981', '#06b6d4', '#3b82f6', // Amber, Emerald, Cyan, Blue
  '#475569', '#1e293b', '#ec4899', '#f97316'  // Slate, Dark, Pink, Orange
];

const funcs = [
  { label: 'Texto', type: 'text', icon: 'pi-align-left' },
  { label: 'Tabla', type: 'table', icon: 'pi-table' },
  { label: 'Mapa', type: 'map', icon: 'pi-map' },
  { label: 'Imagen', type: 'image', icon: 'pi-image' },
  { label: 'Checklist', type: 'checklist', icon: 'pi-check-square' },
  { label: 'Código', type: 'code', icon: 'pi-code' },
  { label: 'Calendario', type: 'calendar', icon: 'pi-calendar' },
  { label: 'Lista', type: 'list', icon: 'pi-list' },
  { label: 'Ciclo', type: 'cycle', icon: 'pi-sync' }
];
</script>
