<template>
  <!-- 1. Bloque de Carga -->
  <div v-if="store.loading" class="flex-1 flex flex-col items-center justify-center bg-transparent transition-colors duration-500">
    <div class="flex flex-col items-center gap-4">
      <i class="pi pi-spin pi-spinner text-4xl text-indigo-500 mb-4"></i>
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest animate-pulse">
        Sincronizando Bucle...
      </p>
    </div>
  </div>

  <!-- 2. Bloque del Editor -->
  <div v-else-if="store.activeSub" class="flex-1 bg-white flex flex-col h-screen relative ltr overflow-hidden">
    
    <!-- Canvas de Edición (Notion-style) -->
    <div class="flex-1 overflow-y-auto px-12 py-20 scroll-smooth custom-scrollbar">
      <div class="max-w-3xl mx-auto">
        
        <!-- Encabezado de Metadatos -->
        <header class="group mb-12 animate-in-fade">
          <div class="mb-6">
            <EmojiPicker v-model="store.activeSub.emoji" />
          </div>
          
          <input 
            v-model="store.activeSub.nombre"
            class="text-5xl font-black border-none focus:ring-0 p-0 w-full bg-transparent text-slate-900 tracking-tight mb-2 placeholder-slate-100"
            placeholder="Título del evento..."
          />
          
          <textarea 
            v-model="store.activeSub.descripcion"
            class="w-full mt-4 border-none focus:ring-0 p-0 bg-transparent text-slate-400 text-xl resize-none italic leading-relaxed"
            placeholder="Añade una descripción corta..."
            rows="1"
          ></textarea>
        </header>

        <!-- Bloques de Contenido -->
        <draggable 
          v-model="draggableBlocks" 
          item-key="id"
          class="space-y-6 min-h-[500px]"
          handle=".drag-handle"
          ghost-class="drag-ghost"
          animation="300"
        >
          <template #item="{ element }">
            <div class="group relative p-4 border border-transparent hover:border-slate-200 hover:bg-slate-50/50 rounded-2xl transition-all duration-300">
              <!-- Tirador de arrastre (Drag Handle) -->
              <div class="drag-handle absolute top-3 right-3 opacity-0 group-hover:opacity-100 cursor-move text-slate-400 hover:text-slate-600 transition-all p-1.5 bg-white rounded-lg shadow-sm border border-slate-100 z-20 hover:scale-110 hover:shadow-md">
                <i class="pi pi-arrows-alt text-xs"></i>
              </div>
              <BlockRenderer 
                :block="element" 
              />
            </div>
          </template>
        </draggable>
        
        <!-- Botón Añadir Propiedad -->
        <button 
          @click="store.addBlock('text')" 
          class="flex items-center gap-2 text-slate-400 hover:text-slate-600 font-medium text-sm p-2 rounded-lg hover:bg-slate-50 transition-colors mt-2 mb-4"
        >
          <i class="pi pi-plus text-xs"></i>
          Añadir una propiedad
        </button>

        <!-- Área de click para añadir bloque al final -->
        <div 
          @click="focusLast" 
          class="h-40 cursor-text group/area relative mt-6"
          @keyup.slash="store.showSlashMenu = true"
        >
           <div class="absolute inset-x-0 top-0 h-px bg-slate-50 opacity-0 group-hover/area:opacity-100 transition-opacity"></div>
           <p class="text-[10px] text-slate-300 mt-4 opacity-0 group-hover/area:opacity-100 transition-opacity uppercase font-bold tracking-widest">
             Escribe '/' para insertar comandos...
           </p>
        </div>

        <!-- Slash Menu Flotante -->
        <SlashMenu 
          v-if="store.showSlashMenu" 
          @select="store.addBlock" 
          @close="store.showSlashMenu = false"
        />
      </div>
    </div>
  </div>

  <!-- 3. Empty State del Editor -->
  <div v-else class="flex-1 flex flex-col items-center justify-center text-center p-12 bg-transparent transition-colors duration-500">
    <div class="w-24 h-24 bg-white rounded-[2.5rem] shadow-sm flex items-center justify-center mb-8 border border-slate-50">
      <i class="pi pi-pencil text-slate-200 text-4xl"></i>
    </div>
    <h3 class="text-xl font-black text-slate-800 mb-2 uppercase tracking-tighter">Editor Dinámico</h3>
    <p class="text-xs text-slate-400 max-w-[200px] leading-relaxed font-medium">
      Selecciona un evento del dashboard para configurar sus funcionalidades.
    </p>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import debounce from 'lodash.debounce';
import draggable from 'vuedraggable';
import EmojiPicker from '@/components/dynamic/EmojiPicker.vue';
import BlockRenderer from '@/components/dynamic/BlockRenderer.vue';
import SlashMenu from '@/components/dynamic/SlashMenu.vue';

const store = useCategoryStore();

const draggableBlocks = computed({
  get() {
    if (!store.activeSub?.blocks) return [];
    return store.activeSub.blocks.filter(b => b.role !== 'main-title');
  },
  set(newValue) {
    if (!store.activeSub) return;
    const mainTitleBlock = store.activeSub.blocks.find(b => b.role === 'main-title');
    if (mainTitleBlock) {
      store.activeSub.blocks = [mainTitleBlock, ...newValue];
    } else {
      store.activeSub.blocks = newValue;
    }
  }
});

watch(
  () => store.activeSub,
  debounce((newVal) => {
    if (newVal) {
      store.saveActiveSub();
      console.log('Sincronizando cambios con Supabase/PostgreSQL...');
    }
  }, 1000),
  { deep: true }
);

const focusLast = () => {
  if (store.activeSub && store.activeSub.blocks.length === 0) {
    store.addBlock('text');
  }
};
</script>

<style scoped>
.drag-ghost {
  opacity: 0.4;
  background-color: #f8fafc; /* slate-50 */
  border: 2px dashed #cbd5e1; /* slate-300 */
  border-radius: 1rem; /* matches rounded-2xl */
}
</style>