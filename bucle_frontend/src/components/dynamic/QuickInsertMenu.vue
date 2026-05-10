<template>
  <transition name="fade-slide">
    <div 
      class="fixed z-[9999] bg-white/80 backdrop-blur-xl border border-slate-200 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] rounded-[2rem] p-4 grid grid-cols-3 gap-3"
      :style="{ top: `${y}px`, left: `${x}px`, transform: 'translate(-50%, -50%)' }"
    >
      <button 
        v-for="tool in tools" 
        :key="tool.type"
        @click.stop="$emit('select', tool.type)"
        class="w-14 h-14 flex flex-col items-center justify-center rounded-2xl bg-white hover:bg-indigo-50 border border-slate-100 hover:border-indigo-100 text-slate-400 hover:text-indigo-600 transition-all shadow-sm hover:shadow-md group active:scale-95"
        :title="tool.label"
      >
        <i :class="['pi', tool.icon, 'text-lg group-hover:scale-110 transition-transform']"></i>
        <span class="text-[8px] font-black uppercase tracking-widest mt-1 opacity-0 group-hover:opacity-100 transition-opacity">{{ tool.label }}</span>
      </button>
    </div>
  </transition>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';

defineProps({
  x: { type: Number, required: true },
  y: { type: Number, required: true }
});

const emit = defineEmits(['select', 'close']);

const tools = [
  { label: 'Texto', type: 'text', icon: 'pi-align-left' },
  { label: 'Imagen', type: 'image', icon: 'pi-image' },
  { label: 'Checklist', type: 'checklist', icon: 'pi-check-square' },
  { label: 'Mapa', type: 'map', icon: 'pi-map' },
  { label: 'Calendario', type: 'calendar', icon: 'pi-calendar' },
  { label: 'Ciclo', type: 'cycle', icon: 'pi-sync' }
];

const closeMenu = () => emit('close');

onMounted(() => {
  document.addEventListener('click', closeMenu);
});

onUnmounted(() => {
  document.removeEventListener('click', closeMenu);
});
</script>
