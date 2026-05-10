<template>
  <div class="flex flex-col w-full h-full min-h-[150px]">
    <!-- Encabezado sutil del frame (opcional) -->
    <div class="mb-2 flex items-center justify-between">
      <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="pi pi-expand mr-1"></i> Contenedor</h4>
    </div>
    
    <!-- Área de arrastre del Frame -->
    <draggable 
      v-model="block.content.blocks" 
      item-key="id"
      class="flex flex-wrap gap-4 p-4 border-2 border-dashed border-slate-200 rounded-xl flex-1 bg-slate-50/30 transition-all duration-300 min-h-[150px]"
      :class="{'ring-4 ring-indigo-100 border-indigo-300 bg-indigo-50/20': isDragOver}"
      group="blocks"
      handle=".drag-handle"
      ghost-class="drag-ghost"
      animation="200"
      @start="isDragOver = true"
      @end="onDragEnd"
      @change="onChange"
      @dblclick="openQuickMenu"
    >
      <template #item="{ element }">
        <div 
          class="group relative p-4 border transition-all duration-300 min-w-[200px] flex-1 resizable-block bg-white shadow-sm rounded-2xl"
          :class="[
             store.selectedBlockIds.has(element.id) 
               ? 'border-indigo-400 shadow-lg ring-4 ring-indigo-50 scale-[1.02] z-30' 
               : 'border-transparent hover:border-slate-200 hover:bg-slate-50/50'
          ]"
          :style="{ width: element.style?.width || '100%', height: element.style?.height || 'auto' }"
          @mouseup="handleResize(element, $event)"
          @dblclick.stop="store.toggleBlockSelection(element.id)"
        >
          <!-- Controles del bloque (Arrastrar y Eliminar) -->
          <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 flex items-center gap-2 z-20">
            <!-- Botón Eliminar -->
            <button 
              @click="removeBlock(element.id)"
              class="text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition-all p-1.5 bg-white rounded-lg shadow-sm border border-slate-100 hover:scale-110 hover:shadow-md"
              title="Eliminar bloque"
            >
              <i class="pi pi-trash text-xs"></i>
            </button>
            <!-- Tirador de arrastre (Solo si está seleccionado) -->
            <div 
              v-if="store.selectedBlockIds.has(element.id)"
              class="drag-handle cursor-move text-indigo-600 hover:text-indigo-800 transition-all p-1.5 bg-indigo-50 rounded-lg shadow-sm border border-indigo-200 hover:scale-110 animate-in-fade"
              title="Arrastrar bloque"
            >
              <i class="pi pi-arrows-alt text-xs animate-pulse"></i>
            </div>
          </div>
          <!-- Recursividad controlada -->
          <BlockRenderer :block="element" class="w-full h-full flex flex-col" />
          <!-- Icono de Redimensionamiento -->
          <div class="absolute bottom-1 right-1 opacity-0 group-hover:opacity-100 pointer-events-none text-slate-300">
            <i class="pi pi-angle-double-down-right text-[10px]"></i>
          </div>
        </div>
      </template>
    </draggable>

    <!-- Menú de Inserción Rápida -->
    <QuickInsertMenu 
      v-if="showMenu" 
      :x="menuX" 
      :y="menuY" 
      @select="handleQuickInsert" 
      @close="showMenu = false" 
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import draggable from 'vuedraggable';
import BlockRenderer from './BlockRenderer.vue';
import QuickInsertMenu from './QuickInsertMenu.vue';

const props = defineProps({
  block: { type: Object, required: true },
  readOnly: { type: Boolean, default: false }
});

const store = useCategoryStore();
const isDragOver = ref(false);
const menuX = ref(0);
const menuY = ref(0);
const showMenu = ref(false);

const openQuickMenu = (e) => {
  if (e.target !== e.currentTarget) return; // Solo clics en el fondo (.self)
  menuX.value = e.clientX;
  menuY.value = e.clientY;
  showMenu.value = true;
};

const handleQuickInsert = (type) => {
  store.addBlockToFrame(props.block.id, type);
  showMenu.value = false;
};

const removeBlock = (id) => {
  if (!props.block.content.blocks) return;
  // eslint-disable-next-line vue/no-mutating-props
  props.block.content.blocks = props.block.content.blocks.filter(b => b.id !== id);
  store.saveActiveSub();
};

const onDragEnd = () => {
  isDragOver.value = false;
  store.saveActiveSub();
};

const onChange = () => {
  store.saveActiveSub();
};

const handleResize = (element, event) => {
  const el = event.currentTarget;
  if (!el) return;
  
  const newWidth = el.style.width;
  const newHeight = el.style.height;

  if (!element.style || typeof element.style !== 'object') {
    element.style = { format: element.style || 'p', width: '100%', height: 'auto' };
  }

  if ((newWidth && element.style.width !== newWidth) || (newHeight && element.style.height !== newHeight)) {
    if (newWidth) element.style.width = newWidth;
    if (newHeight) element.style.height = newHeight;
    store.saveActiveSub();
  }
};
</script>

<style scoped>
.drag-ghost {
  opacity: 0.4;
  background-color: #f8fafc;
  border: 2px dashed #cbd5e1;
  border-radius: 1rem;
}
</style>
