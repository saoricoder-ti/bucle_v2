<template>
  <div 
    @click="store.openEditor(data)" 
    class="group/card relative bg-white rounded-[2.5rem] p-8 h-72 border border-slate-100 hover:border-indigo-500/30 transition-all duration-500 cursor-pointer overflow-hidden shadow-2xl"
  >
    <!-- Decoración de fondo dinámica -->
    <div 
      class="absolute -right-4 -top-4 w-32 h-32 rounded-full blur-3xl transition-all duration-700"
      :style="{ backgroundColor: (store.activeCategory?.color || '#6366f1') + '11' }"
    ></div>
    
    <!-- Icono flotante superior -->
    <div class="text-5xl mb-6 transform group-hover/card:scale-110 group-hover/card:rotate-6 transition-transform duration-500">
      {{ data.emoji || '📄' }}
    </div>

    <!-- Texto con mejor jerarquía -->
    <div class="space-y-3 relative z-10">
      <!-- MODO EDICIÓN INLINE -->
      <div v-if="store.subEditingId === data.id" class="relative">
        <input 
          v-model="data.nombre"
          @blur="store.renameSubcategory(data.id, data.nombre)"
          @keyup.enter="store.renameSubcategory(data.id, data.nombre)"
          class="bg-slate-50 text-slate-900 text-xl font-black border-none ring-2 ring-indigo-500 rounded-xl px-2 w-full focus:ring-indigo-500"
          auto-focus
          @click.stop
        />
      </div>
      <h3 
        v-else
        class="text-xl font-black transition-colors tracking-tight leading-tight"
        :style="{ color: store.activeCategory?.id ? '#0f172a' : '#6366f1' }"
        :class="{'group-hover/card:text-indigo-600': !store.activeCategory?.color}"
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

    <!-- BOTÓN DE ACCIÓN (TUERCA) -->
    <div 
      @click.stop="toggleMenu"
      class="absolute right-6 top-6 w-10 h-10 bg-white rounded-xl shadow-xl border border-gray-50 flex items-center justify-center opacity-0 group-hover/card:opacity-100 transition-all duration-300 hover:scale-125 hover:rotate-90 z-20 cursor-pointer"
      :style="{ color: store.activeCategory?.color || '#6366f1' }"
    >
      <i class="pi pi-cog text-xs"></i>
    </div>

    <!-- Botón de edición fantasma (Original) -->
    <div 
      class="absolute bottom-8 right-8 w-12 h-12 rounded-2xl flex items-center justify-center opacity-0 group-hover/card:opacity-100 translate-y-4 group-hover/card:translate-y-0 transition-all duration-500 shadow-xl"
      :style="{ backgroundColor: store.activeCategory?.color || '#6366f1', boxShadow: `0 10px 20px ${store.activeCategory?.color || '#6366f1'}44` }"
    >
      <i class="pi pi-pencil text-white text-xs"></i>
    </div>

    <!-- DROPDOWN DE ACCIONES (FIXED) -->
    <Teleport to="body">
      <transition name="fade-slide">
        <div 
          v-if="showMenu"
          :style="{ top: menuPos.y + 'px', left: menuPos.x + 'px' }"
          class="fixed w-44 bg-white border border-gray-100 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.2)] z-[999] p-2 animate-modal-enter"
        >
          <div class="px-3 py-2 border-b border-gray-50 mb-1">
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Gestión de Evento</p>
          </div>
          
          <button @click="startRename" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-50 flex items-center gap-3 group">
            <i class="pi pi-pencil text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
            <span class="text-[10px] font-bold text-slate-600">Renombrar</span>
          </button>
          
          <button @click="store.duplicateSubcategory(data.id); showMenu = false" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-50 flex items-center gap-3 group">
            <i class="pi pi-copy text-[10px] text-slate-400 group-hover:text-indigo-600"></i>
            <span class="text-[10px] font-bold text-slate-600">Duplicar</span>
          </button>

          <div class="h-px bg-gray-50 my-1"></div>

          <button @click="store.deleteSubcategory(data.id); showMenu = false" class="w-full text-left px-3 py-2 rounded-lg hover:bg-red-50 flex items-center gap-3 group">
            <i class="pi pi-trash text-[10px] text-slate-400 group-hover:text-red-500"></i>
            <span class="text-[10px] font-bold text-slate-600 group-hover:text-red-600">Eliminar</span>
          </button>
        </div>
      </transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';

const props = defineProps({
  data: {
    type: Object,
    required: true
  }
});

const store = useCategoryStore();
const showMenu = ref(false);
const menuPos = ref({ x: 0, y: 0 });

const toggleMenu = (event) => {
  const rect = event.currentTarget.getBoundingClientRect();
  menuPos.value = {
    x: rect.right + 10,
    y: rect.top
  };
  showMenu.value = !showMenu.value;
};

const startRename = () => {
  store.subEditingId = props.data.id;
  showMenu.value = false;
};

const handleClickOutside = (e) => {
  if (!e.target.closest('.fixed') && !e.target.closest('.pi-cog')) {
    showMenu.value = false;
  }
};

onMounted(() => window.addEventListener('click', handleClickOutside));
onUnmounted(() => window.removeEventListener('click', handleClickOutside));
</script>