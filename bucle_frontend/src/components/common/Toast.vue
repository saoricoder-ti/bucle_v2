<template>
  <transition name="toast-fade">
    <div v-if="store.toast.show" 
         class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[200] flex items-center gap-4 px-6 py-4 bg-slate-900 text-white rounded-3xl shadow-2xl border border-slate-800 backdrop-blur-xl">
      <div class="w-10 h-10 rounded-2xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
        <i class="pi pi-check text-xs"></i>
      </div>
      <div class="flex flex-col pr-4">
        <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50 mb-0.5">Notificación</span>
        <span class="text-sm font-bold tracking-tight">{{ store.toast.message }}</span>
      </div>
      
      <!-- Pequeña barra de tiempo decorativa -->
      <div class="absolute bottom-0 left-0 h-1 bg-white/20 rounded-full animate-toast-progress"></div>
    </div>
  </transition>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';
const store = useCategoryStore();
</script>
