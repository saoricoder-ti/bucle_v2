<template>
  <div class="my-6 p-8 bg-white dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] shadow-sm flex flex-col items-stretch gap-6 group">
    
    <!-- Sección Superior: Gráfico y Lista de Métricas -->
    <div class="flex flex-col md:flex-row items-center gap-8">
      <div class="relative w-32 h-32 flex items-center justify-center flex-shrink-0">
        <svg class="w-full h-full transform -rotate-90">
          <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100 dark:text-slate-800" />
          <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" 
                  :stroke-dasharray="364" :stroke-dashoffset="364 - (364 * percentage) / 100"
                  class="transition-all duration-1000" :class="colorClass" stroke-linecap="round" />
        </svg>
        <span class="absolute text-2xl font-black text-slate-900 dark:text-white">{{ Math.round(percentage) }}%</span>
      </div>

      <div class="flex-1 w-full space-y-4">
        <input v-model="block.content.label" @blur="store.saveActiveSub" class="text-xl font-black bg-transparent border-none p-0 w-full focus:ring-0" placeholder="Nombre del Grupo de Servicio..." />
        
        <div class="space-y-2">
          <div v-for="(item, index) in block.content.items" :key="index" class="flex flex-wrap items-center gap-2 bg-slate-50 p-2 rounded-xl text-xs font-bold text-slate-500 group/item transition-colors hover:bg-slate-100">
            <input v-model="item.name" @blur="store.saveActiveSub" class="bg-transparent border-none p-1 focus:ring-1 focus:ring-indigo-100 rounded flex-1 min-w-[100px] text-slate-700" placeholder="Medida..." />
            
            <div class="flex items-center gap-1 bg-white px-2 py-1 rounded-lg border border-slate-100 shadow-sm" :class="{ 'ring-2 ring-red-400': isAlertTriggered(item) }">
              <input type="number" v-model.number="item.current" @blur="store.saveActiveSub" class="bg-transparent border-none p-0 focus:ring-0 w-12 text-right font-black" :class="isAlertTriggered(item) ? 'text-red-600' : 'text-indigo-600'" placeholder="0" />
              <span class="opacity-30">/</span>
              <input type="number" v-model.number="item.target" @blur="store.saveActiveSub" class="bg-transparent border-none p-0 focus:ring-0 w-16" placeholder="Meta" />
            </div>
            
            <div class="flex items-center gap-2">
              <input v-model="item.unit" @blur="store.saveActiveSub" class="bg-transparent border-none p-1 focus:ring-1 focus:ring-indigo-100 rounded w-12 uppercase tracking-widest text-[9px]" placeholder="UNID" />
              <i v-if="isAlertTriggered(item)" class="pi pi-bell text-red-500 animate-wiggle" title="¡Revisión Próxima!"></i>
            </div>
            
            <button @click="removeItem(index)" class="opacity-0 group-hover/item:opacity-100 text-slate-300 hover:text-red-500 transition-opacity p-1">
              <i class="pi pi-trash"></i>
            </button>
          </div>
        </div>
        
        <button @click="addItem" class="text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors flex items-center gap-2">
          <i class="pi pi-plus-circle"></i> Agregar Métrica
        </button>
      </div>
    </div>

    <!-- Sección Inferior: Alerta Lógica de Ciclo -->
    <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center gap-6">
      <div class="flex items-center gap-3">
        <label class="relative inline-flex items-center cursor-pointer">
          <input type="checkbox" v-model="block.content.hasReminder" @change="store.saveActiveSub" class="sr-only peer">
          <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
        </label>
        <span class="text-[9px] font-black uppercase tracking-widest" :class="block.content.hasReminder ? 'text-indigo-600' : 'text-slate-400'">Alerta Preventiva</span>
      </div>
      
      <transition name="fade">
        <div v-if="block.content.hasReminder" class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-indigo-50/50 px-3 py-1.5 rounded-xl border border-indigo-100">
          <i class="pi pi-bell text-indigo-400"></i>
          <span>Avisar</span>
          <input type="number" v-model.number="block.content.reminderThreshold" @blur="store.saveActiveSub" class="bg-white border border-slate-100 p-0.5 w-16 text-center focus:ring-1 focus:ring-indigo-200 rounded text-indigo-600 shadow-sm outline-none" placeholder="500" />
          <select v-model="block.content.reminderUnit" @change="store.saveActiveSub" class="bg-white border border-slate-100 p-0.5 focus:ring-1 focus:ring-indigo-200 rounded text-slate-600 outline-none shadow-sm cursor-pointer">
            <option v-for="unit in availableUnits" :key="unit" :value="unit">{{ unit || 'ud' }}</option>
          </select>
          <span>antes de la meta</span>
        </div>
      </transition>
    </div>

  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';

const props = defineProps(['block']);
const store = useCategoryStore();

// Migración para bloques antiguos (legacy fallback)
if (!props.block.content.items) {
  props.block.content.items = [
    { 
      name: 'General', 
      current: props.block.content.current || 0, 
      target: props.block.content.target || 100, 
      unit: props.block.content.unit || 'uds' 
    }
  ];
}
if (props.block.content.hasReminder === undefined) {
  props.block.content.hasReminder = false;
  props.block.content.reminderThreshold = 500;
  props.block.content.reminderUnit = props.block.content.items[0]?.unit || 'km';
}

const availableUnits = computed(() => {
  const units = props.block.content.items.map(i => i.unit).filter(Boolean);
  return [...new Set(units)];
});

const isAlertTriggered = (item) => {
  if (!props.block.content.hasReminder) return false;
  if (item.unit !== props.block.content.reminderUnit) return false;
  const remaining = item.target - item.current;
  return remaining <= props.block.content.reminderThreshold;
};

const totalCurrent = computed(() => {
  return props.block.content.items.reduce((acc, item) => acc + (Number(item.current) || 0), 0);
});

const totalTarget = computed(() => {
  return props.block.content.items.reduce((acc, item) => acc + (Number(item.target) || 0), 0);
});

const percentage = computed(() => {
  if (totalTarget.value === 0) return 0;
  const p = (totalCurrent.value / totalTarget.value) * 100;
  return Math.min(Math.max(p, 0), 100);
});

const colorClass = computed(() => {
  if (percentage.value <= 70) return 'text-emerald-500';
  if (percentage.value <= 90) return 'text-orange-500';
  return 'text-red-500';
});

const addItem = () => {
  props.block.content.items.push({ name: 'Nueva', current: 0, target: 100, unit: 'ud' });
  store.saveActiveSub();
};

const removeItem = (index) => {
  props.block.content.items.splice(index, 1);
  store.saveActiveSub();
};
</script>
