<template>
  <div class="flex-1 transparent-canvas p-12 overflow-y-auto transition-colors duration-500 custom-scrollbar flex items-center justify-center h-full">
    <div class="max-w-md w-full text-center flex flex-col items-center">
      
      <!-- Icono/Ilustración -->
      <div class="w-32 h-32 bg-white rounded-[3rem] shadow-sm flex items-center justify-center mb-8 border border-slate-50 relative group">
        <div class="absolute inset-0 bg-indigo-50 rounded-[3rem] scale-0 group-hover:scale-105 transition-transform duration-500 ease-out"></div>
        <i class="pi pi-folder-open text-indigo-400 text-6xl relative z-10 group-hover:scale-110 transition-transform duration-300"></i>
        <!-- Decoraciones -->
        <div class="absolute -top-4 -right-4 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center animate-bounce" style="animation-delay: 0.1s;">
          <span class="text-amber-500 text-xs">✨</span>
        </div>
        <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center animate-bounce" style="animation-delay: 0.3s;">
          <span class="text-emerald-500 text-[10px]">🚀</span>
        </div>
      </div>

      <!-- Texto Principal -->
      <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Bienvenido a Bucle</h1>
      <p class="text-slate-500 text-sm mb-10 leading-relaxed font-medium">
        Tu espacio de trabajo está vacío. Crea tu primera categoría para empezar a organizar tus eventos, documentos y flujos de trabajo de manera inteligente.
      </p>

      <!-- Botón de Acción -->
      <button 
        @click="store.triggerCategoryModal()"
        class="flex items-center justify-center gap-3 bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 shadow-xl hover:shadow-indigo-500/30 transition-all hover:-translate-y-1 active:scale-95 group w-full sm:w-auto"
      >
        <i class="pi pi-plus-circle group-hover:rotate-90 transition-transform duration-300"></i>
        <span>Crear mi primer Bucle</span>
      </button>

    </div>
  </div>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';

const store = useCategoryStore();
</script>
