<template>
  <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 select-none">
    <header class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ monthName }}</h3>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ currentYear }}</p>
      </div>
      <div class="flex gap-2">
        <button @click="changeMonth(-1)" class="p-2 hover:bg-slate-50 rounded-xl transition-colors">
          <i class="pi pi-chevron-left text-xs text-slate-400"></i>
        </button>
        <button @click="changeMonth(1)" class="p-2 hover:bg-slate-50 rounded-xl transition-colors">
          <i class="pi pi-chevron-right text-xs text-slate-400"></i>
        </button>
      </div>
    </header>

    <div class="grid grid-cols-7 gap-1 mb-2">
      <div v-for="day in daysOfWeek" :key="day" class="text-center text-[10px] font-black text-slate-300 uppercase tracking-widest py-2">
        {{ day }}
      </div>
    </div>

    <div class="grid grid-cols-7 gap-1">
      <!-- Celdas vacías para el inicio del mes -->
      <div v-for="n in firstDayOfMonth" :key="'blank-'+n" class="aspect-square"></div>
      
      <!-- Días del mes -->
      <div 
        v-for="day in daysInMonth" :key="day"
        @click="selectDay(day)"
        class="aspect-square flex flex-col items-center justify-center rounded-2xl cursor-pointer transition-all relative group"
        :class="isToday(day) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-50 text-slate-600'"
      >
        <span class="text-xs font-bold">{{ day }}</span>
        
        <!-- Indicador de evento (simulado por ahora) -->
        <div v-if="hasEvent(day)" class="absolute bottom-2 w-1 h-1 rounded-full bg-indigo-400"></div>
      </div>
    </div>

    <footer class="mt-6 pt-6 border-t border-slate-50 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
          <i class="pi pi-calendar text-indigo-500 text-xs"></i>
        </div>
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Gestión Visual de Fechas</p>
      </div>
      <button class="text-[9px] font-black text-indigo-600 uppercase hover:underline">Ver Agenda</button>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  block: Object,
  readOnly: Boolean
});

const date = ref(new Date(props.block?.content?.selectedDate || new Date()));
const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];

const monthName = computed(() => {
  return date.value.toLocaleString('es-ES', { month: 'long' }).charAt(0).toUpperCase() + 
         date.value.toLocaleString('es-ES', { month: 'long' }).slice(1);
});

const currentYear = computed(() => date.value.getFullYear());

const daysInMonth = computed(() => {
  return new Date(date.value.getFullYear(), date.value.getMonth() + 1, 0).getDate();
});

const firstDayOfMonth = computed(() => {
  return new Date(date.value.getFullYear(), date.value.getMonth(), 1).getDay();
});

const changeMonth = (offset) => {
  date.value = new Date(date.value.getFullYear(), date.value.getMonth() + offset, 1);
};

const isToday = (day) => {
  const today = new Date();
  return today.getDate() === day && 
         today.getMonth() === date.value.getMonth() && 
         today.getFullYear() === date.value.getFullYear();
};

const selectDay = (day) => {
  // Aquí se podría implementar la lógica para añadir eventos al día seleccionado
  console.log("Día seleccionado:", day, monthName.value, currentYear.value);
};

const hasEvent = (day) => {
  // Simulación de eventos
  return [5, 12, 18, 25].includes(day);
};
</script>
