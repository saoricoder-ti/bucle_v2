<template>
  <div class="my-4 p-6 bg-yellow-50/50 dark:bg-yellow-900/10 border border-yellow-100 dark:border-yellow-900/30 rounded-[2rem] flex items-center gap-6 group shadow-sm transition-all hover:shadow-md">
    <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-800/30 rounded-2xl flex items-center justify-center text-yellow-500 shadow-sm flex-shrink-0">
      <i class="pi pi-bell text-2xl" :class="{ 'animate-bounce': block.content.isActive && isToday }"></i>
    </div>
    
    <div class="flex-1 space-y-3">
      <input v-model="block.content.label" @blur="store.saveActiveSub" class="text-xl font-black bg-transparent border-none p-0 w-full focus:ring-0 text-slate-800" placeholder="Título del recordatorio..." />
      
      <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-500">
        <!-- Selector de Fecha -->
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-yellow-100 shadow-sm hover:border-yellow-300 transition-colors">
          <i class="pi pi-calendar text-yellow-400"></i>
          <input type="date" v-model="block.content.date" @blur="store.saveActiveSub" class="bg-transparent border-none p-0 focus:ring-0 text-slate-600 outline-none w-auto" />
        </div>
        
        <!-- Selector de Hora -->
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-yellow-100 shadow-sm hover:border-yellow-300 transition-colors">
          <i class="pi pi-clock text-yellow-400"></i>
          <input type="time" v-model="block.content.time" @blur="store.saveActiveSub" class="bg-transparent border-none p-0 focus:ring-0 text-slate-600 outline-none w-auto" />
        </div>

        <!-- Selector de Frecuencia Calendario -->
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-yellow-100 shadow-sm hover:border-yellow-300 transition-colors">
          <i class="pi pi-sync text-yellow-400"></i>
          <select v-model="block.content.frequency" @change="store.saveActiveSub" class="bg-transparent border-none p-0 focus:ring-0 text-slate-600 outline-none cursor-pointer">
            <option value="once">Una vez</option>
            <option value="daily">Diario</option>
            <option value="weekly">Semanal</option>
            <option value="monthly">Mensual</option>
            <option value="yearly">Anual</option>
          </select>
        </div>
      </div>
    </div>
    
    <!-- Interruptor de Activación -->
    <div class="flex flex-col items-center gap-2 border-l border-yellow-200/50 pl-6 ml-2">
      <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" v-model="block.content.isActive" @change="store.saveActiveSub" class="sr-only peer">
        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-400"></div>
      </label>
      <span class="text-[9px] font-black tracking-widest uppercase" :class="block.content.isActive ? 'text-yellow-600' : 'text-slate-400'">
        {{ block.content.isActive ? 'Activo' : 'Inactivo' }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';

const props = defineProps(['block']);
const store = useCategoryStore();

const isToday = computed(() => {
  if (!props.block.content.date) return false;
  const today = new Date().toISOString().split('T')[0];
  return props.block.content.date === today;
});
</script>
