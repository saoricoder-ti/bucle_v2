<template>
  <div 
    @click="store.openEditor(data)" 
    class="group relative bg-white rounded-[2.5rem] p-8 h-72 border border-slate-100 hover:border-indigo-500/30 transition-all duration-500 cursor-pointer overflow-hidden shadow-2xl"
  >
    <!-- Decoración de fondo dinámica -->
    <div 
      class="absolute -right-4 -top-4 w-32 h-32 rounded-full blur-3xl transition-all duration-700"
      :style="{ backgroundColor: (store.activeCategory?.color || '#6366f1') + '11' }"
    ></div>
    
    <!-- Icono flotante superior -->
    <div class="text-5xl mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
      {{ data.emoji || '📄' }}
    </div>

    <!-- Texto con mejor jerarquía -->
    <div class="space-y-3 relative z-10">
      <h3 
        class="text-xl font-black transition-colors tracking-tight leading-tight"
        :style="{ color: store.activeCategory?.id ? '#0f172a' : '#6366f1' }"
        :class="{'group-hover:text-indigo-600': !store.activeCategory?.color}"
      >
        {{ data.nombre }}
      </h3>
      <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 italic font-medium">
        "{{ data.descripcion || 'Sin descripción detallada' }}"
      </p>
    </div>

    <!-- Tags en la parte inferior -->
    <div class="absolute bottom-8 left-8 flex gap-2">
      <span 
        class="px-3 py-1 rounded-full bg-slate-50 text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] border border-slate-100 shadow-inner"
        :style="{ borderColor: (store.activeCategory?.color || '#f1f5f9') + '33' }"
      >
        #{{ store.activeCategory?.nombre || 'General' }}
      </span>
    </div>

    <!-- Botón de acción fantasma -->
    <div 
      class="absolute bottom-8 right-8 w-12 h-12 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500 shadow-xl"
      :style="{ backgroundColor: store.activeCategory?.color || '#6366f1', boxShadow: `0 10px 20px ${store.activeCategory?.color || '#6366f1'}44` }"
    >
      <i class="pi pi-pencil text-white text-xs"></i>
    </div>
  </div>
</template>

<script setup>
import { useCategoryStore } from '@/stores/categoryStore';

const store = useCategoryStore();

defineProps({
  data: {
    type: Object,
    required: true
  }
});
</script>