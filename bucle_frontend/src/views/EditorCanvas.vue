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
  <div v-else-if="store.activeSub" class="flex-1 bg-transparent flex flex-col h-screen relative ltr overflow-hidden">
    

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
            class="text-5xl font-black border-none focus:ring-0 p-0 w-full bg-transparent text-slate-900 dark:text-white tracking-tight mb-2 placeholder-slate-100 dark:placeholder-slate-800"
            placeholder="Título del evento..."
          />
          
          <textarea 
            v-model="store.activeSub.descripcion"
            class="w-full mt-4 border-none focus:ring-0 p-0 bg-transparent text-slate-400 dark:text-slate-500 text-xl resize-none italic leading-relaxed"
            placeholder="Añade una descripción corta..."
            rows="1"
          ></textarea>
        </header>

        <!-- Bloques de Contenido -->
        <div class="space-y-6 min-h-[500px]">
          <BlockRenderer 
            v-for="block in contentBlocks" 
            :key="block.id" 
            :block="block" 
          />
          
          <!-- Área de click para añadir bloque al final -->
          <div 
            @click="focusLast" 
            class="h-40 cursor-text group/area relative"
            @keyup.slash="store.showSlashMenu = true"
          >
             <div class="absolute inset-x-0 top-0 h-px bg-slate-50 dark:bg-slate-800 opacity-0 group-hover/area:opacity-100 transition-opacity"></div>
             <p class="text-[10px] text-slate-300 dark:text-slate-700 mt-4 opacity-0 group-hover/area:opacity-100 transition-opacity uppercase font-bold tracking-widest">
               Escribe '/' para insertar comandos...
             </p>
          </div>
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
    <div class="w-24 h-24 bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm flex items-center justify-center mb-8 border border-slate-50 dark:border-slate-800">
      <i class="pi pi-pencil text-slate-200 dark:text-slate-800 text-4xl"></i>
    </div>
    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-tighter">Editor Dinámico</h3>
    <p class="text-xs text-slate-400 dark:text-slate-500 max-w-[200px] leading-relaxed font-medium">
      Selecciona un evento del dashboard para configurar sus funcionalidades.
    </p>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import debounce from 'lodash.debounce';
import EmojiPicker from '@/components/dynamic/EmojiPicker.vue';
import BlockRenderer from '@/components/dynamic/BlockRenderer.vue';
import SlashMenu from '@/components/dynamic/SlashMenu.vue';

const store = useCategoryStore();

/**
 * 1. Filtro Robusto: Evitamos duplicados usando el 'role' del bloque,
 * no su contenido de texto.
 */
const contentBlocks = computed(() => {
  if (!store.activeSub?.blocks) return [];
  // Filtramos por rol fijo para mayor estabilidad
  return store.activeSub.blocks.filter(b => b.role !== 'main-title');
});

/**
 * 2. Persistencia (Auto-guardado): Observamos cambios profundos en la 
 * subcategoría activa y sincronizamos con el backend tras 1s de inactividad.
 */
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