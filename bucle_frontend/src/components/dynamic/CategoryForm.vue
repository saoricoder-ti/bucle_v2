<template>
  <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm animate-modal-enter">
    <div class="w-full max-w-xl bg-white rounded-[3rem] shadow-2xl border border-slate-100 relative flex h-[500px] overflow-visible">
      
      <!-- Botón de Cerrar (Esquina Superior) -->
      <button 
        @click="store.isCreatingCategory = false" 
        class="absolute -top-4 -right-4 w-10 h-10 bg-white rounded-full shadow-xl flex items-center justify-center text-slate-400 hover:text-red-500 transition-all hover:rotate-90 z-[110] border border-slate-50"
      >
        <i class="pi pi-times"></i>
      </button>

      <!-- Franja lateral de color dinámico -->
      <div 
        class="w-32 h-full transition-all duration-700 ease-in-out flex items-center justify-center shrink-0 rounded-l-[3rem]" 
        :style="{ backgroundColor: form.color }"
      >
        <i class="pi pi-box text-white/20 text-4xl animate-pulse"></i>
      </div>

      <!-- Área de Formulario Principal -->
      <div class="flex-1 p-12 relative">
        <!-- Selector de Emoji flotante -->
        <div class="absolute top-8 right-12 scale-110">
          <EmojiPicker v-model="form.emoji" />
        </div>

        <header class="mb-10">
          <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-2">
            {{ store.categoryFormMode === 'edit' ? 'Personalizar Bucle' : 'Configurar Categoría' }}
          </h2>
          <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Identidad Visual</p>
        </header>

        <div class="space-y-8">
          <!-- Campo de Nombre -->
          <div class="group">
            <input 
              v-model="form.nombre" 
              class="w-full text-3xl font-black bg-transparent border-none focus:ring-0 p-0 text-slate-900 placeholder-slate-200 tracking-tighter"
              placeholder="Nombre del Bucle..."
              autofocus
            />
            <div class="h-0.5 w-full bg-slate-100 mt-2 overflow-hidden rounded-full">
               <div class="h-full bg-indigo-500 transition-all duration-700 ease-out" :style="{ width: form.nombre ? '100%' : '0%' }"></div>
            </div>
          </div>

          <!-- Selector de Gama de Colores Ampliada -->
          <div>
            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-4">Gama de Colores</label>
            <div class="grid grid-cols-6 gap-3">
              <button 
                v-for="color in availableColors" :key="color"
                @click="form.color = color"
                class="w-8 h-8 rounded-full border-2 transition-all hover:scale-110 active:scale-90"
                :class="form.color === color ? 'border-slate-900 shadow-xl scale-110 ring-2 ring-indigo-500/20' : 'border-transparent shadow-sm'"
                :style="{ backgroundColor: color }"
              ></button>
            </div>
          </div>
        </div>

        <!-- Acciones Inferiores -->
        <div class="absolute bottom-12 left-12 right-12 flex items-center gap-6">
          <button 
            @click="store.isCreatingCategory = false" 
            class="text-[10px] font-black uppercase text-slate-400 hover:text-slate-600 transition-colors"
          >
            Cancelar
          </button>
          <button 
            @click="handleSave"
            :disabled="!form.nombre || store.loading"
            class="flex-1 py-4 bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-2xl text-[10px] font-black uppercase shadow-lg shadow-indigo-500/20 active:scale-95 transition-all flex items-center justify-center gap-2"
          >
            <i v-if="store.loading" class="pi pi-spin pi-spinner text-xs"></i>
            <span>{{ store.categoryFormMode === 'edit' ? 'Guardar Cambios' : 'Activar Categoría' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import EmojiPicker from './EmojiPicker.vue';

const store = useCategoryStore();

const availableColors = [
  '#6366f1', '#8b5cf6', '#d946ef', '#f43f5e', // Indigo, Violet, Fuchsia, Rose
  '#f59e0b', '#10b981', '#06b6d4', '#3b82f6', // Amber, Emerald, Cyan, Blue
  '#475569', '#1e293b', '#ec4899', '#f97316'  // Slate, Dark, Pink, Orange
];

const form = ref({ 
  nombre: store.categoryFormMode === 'edit' ? store.activeCategory?.nombre : '', 
  emoji: store.categoryFormMode === 'edit' ? store.activeCategory?.emoji : '📁', 
  color: store.categoryFormMode === 'edit' ? (store.activeCategory?.color || '#6366f1') : '#6366f1' 
});

const handleSave = async () => {
  if (!form.value.nombre) return;
  await store.addCategory({ ...form.value });
};
</script>
