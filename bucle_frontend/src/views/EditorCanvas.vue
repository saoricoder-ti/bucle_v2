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
          
          <div class="relative flex items-center group/title mb-4 max-w-full">
            <h1 
              :style="{ 
                color: store.activeSub.color || '#0f172a',
                backgroundColor: store.activeSub.config?.bgColor || 'transparent'
              }" 
              :class="[
                'font-black transition-all duration-300 w-full rounded-2xl p-2 -ml-2',
                store.activeSub.config?.fontSize || 'text-5xl',
                store.activeSub.config?.textAlign || 'text-left',
                store.activeSub.config?.textShadow ? 'drop-shadow-lg' : ''
              ]"
              style="line-height: 1.2"
            >
              {{ store.activeSub.nombre || 'Título del evento...' }}
            </h1>
            
            <button 
              @click="showTitleMenu = !showTitleMenu"
              class="opacity-0 group-hover/title:opacity-100 transition-opacity p-2 text-slate-400 hover:text-slate-600 bg-white/50 backdrop-blur rounded-xl ml-2 shrink-0 border border-transparent hover:border-slate-200 shadow-sm"
              title="Personalizar título"
            >
              <i class="pi pi-ellipsis-v text-sm"></i>
            </button>

            <!-- Menú de personalización (Popover) -->
            <transition name="fade">
              <div v-if="showTitleMenu" class="absolute left-full top-0 ml-4 w-64 bg-white/80 backdrop-blur-xl border border-slate-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] rounded-3xl p-5 z-50">
                <div class="mb-4">
                  <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Tamaño</label>
                  <div class="flex gap-2">
                    <button @click="store.activeSub.config.fontSize = 'text-2xl'; store.saveActiveSub()" :class="{'bg-indigo-50 text-indigo-600': store.activeSub.config.fontSize === 'text-2xl', 'text-slate-500 hover:bg-slate-50': store.activeSub.config.fontSize !== 'text-2xl'}" class="flex-1 py-1 rounded-lg transition-colors text-xs font-bold">P</button>
                    <button @click="store.activeSub.config.fontSize = 'text-4xl'; store.saveActiveSub()" :class="{'bg-indigo-50 text-indigo-600': store.activeSub.config.fontSize === 'text-4xl', 'text-slate-500 hover:bg-slate-50': store.activeSub.config.fontSize !== 'text-4xl'}" class="flex-1 py-1 rounded-lg transition-colors text-xs font-bold">M</button>
                    <button @click="store.activeSub.config.fontSize = 'text-5xl'; store.saveActiveSub()" :class="{'bg-indigo-50 text-indigo-600': store.activeSub.config.fontSize === 'text-5xl', 'text-slate-500 hover:bg-slate-50': store.activeSub.config.fontSize !== 'text-5xl'}" class="flex-1 py-1 rounded-lg transition-colors text-xs font-bold">G</button>
                    <button @click="store.activeSub.config.fontSize = 'text-7xl'; store.saveActiveSub()" :class="{'bg-indigo-50 text-indigo-600': store.activeSub.config.fontSize === 'text-7xl', 'text-slate-500 hover:bg-slate-50': store.activeSub.config.fontSize !== 'text-7xl'}" class="flex-1 py-1 rounded-lg transition-colors text-xs font-bold">X</button>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Alineación</label>
                  <div class="flex gap-2 bg-slate-50 p-1 rounded-xl">
                    <button @click="store.activeSub.config.textAlign = 'text-left'; store.saveActiveSub()" :class="{'bg-white shadow-sm text-indigo-600': store.activeSub.config.textAlign === 'text-left', 'text-slate-500 hover:text-indigo-600': store.activeSub.config.textAlign !== 'text-left'}" class="flex-1 py-1.5 rounded-lg transition-colors"><i class="pi pi-align-left text-xs"></i></button>
                    <button @click="store.activeSub.config.textAlign = 'text-center'; store.saveActiveSub()" :class="{'bg-white shadow-sm text-indigo-600': store.activeSub.config.textAlign === 'text-center', 'text-slate-500 hover:text-indigo-600': store.activeSub.config.textAlign !== 'text-center'}" class="flex-1 py-1.5 rounded-lg transition-colors"><i class="pi pi-align-center text-xs"></i></button>
                    <button @click="store.activeSub.config.textAlign = 'text-right'; store.saveActiveSub()" :class="{'bg-white shadow-sm text-indigo-600': store.activeSub.config.textAlign === 'text-right', 'text-slate-500 hover:text-indigo-600': store.activeSub.config.textAlign !== 'text-right'}" class="flex-1 py-1.5 rounded-lg transition-colors"><i class="pi pi-align-right text-xs"></i></button>
                  </div>
                </div>

                <div class="mb-4 flex items-center justify-between">
                  <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sombra</label>
                  <button 
                    @click="store.activeSub.config.textShadow = !store.activeSub.config.textShadow; store.saveActiveSub()"
                    class="w-10 h-5 rounded-full relative transition-colors"
                    :class="store.activeSub.config.textShadow ? 'bg-indigo-500' : 'bg-slate-200'"
                  >
                    <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 transition-all" :class="store.activeSub.config.textShadow ? 'left-5' : 'left-0.5'"></div>
                  </button>
                </div>

                <div>
                  <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Fondo</label>
                  <div class="flex gap-2">
                    <button @click="store.activeSub.config.bgColor = 'transparent'; store.saveActiveSub()" class="w-6 h-6 rounded-full border border-slate-200 bg-white shadow-sm transition-transform hover:scale-110" title="Ninguno"></button>
                    <button @click="store.activeSub.config.bgColor = '#f8fafc'; store.saveActiveSub()" class="w-6 h-6 rounded-full border border-transparent hover:border-slate-300 bg-slate-50 shadow-sm transition-transform hover:scale-110"></button>
                    <button @click="store.activeSub.config.bgColor = '#eef2ff'; store.saveActiveSub()" class="w-6 h-6 rounded-full border border-transparent hover:border-indigo-300 bg-indigo-50 shadow-sm transition-transform hover:scale-110"></button>
                    <button @click="store.activeSub.config.bgColor = '#fdf4ff'; store.saveActiveSub()" class="w-6 h-6 rounded-full border border-transparent hover:border-fuchsia-300 bg-fuchsia-50 shadow-sm transition-transform hover:scale-110"></button>
                  </div>
                </div>
              </div>
            </transition>
          </div>
          
          <!-- Sistema de Etiquetas -->
          <div class="flex flex-wrap gap-2 mt-4 items-center">
            <span 
              v-for="(tag, index) in store.activeSub.tags || []" 
              :key="index"
              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 text-xs font-semibold tracking-wide"
            >
              {{ tag }}
              <button @click="store.activeSub.tags.splice(index, 1); store.saveActiveSub()" class="hover:text-indigo-900 focus:outline-none transition-colors">
                <i class="pi pi-times text-[10px]"></i>
              </button>
            </span>
            
            <div class="relative flex items-center">
              <button 
                v-if="!isAddingTag" 
                @click="isAddingTag = true; nextTick(() => tagInput?.focus())"
                class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors border border-dashed border-slate-300"
                title="Añadir etiqueta"
              >
                <i class="pi pi-plus text-xs"></i>
              </button>
              <input 
                v-else
                ref="tagInput"
                v-model="newTag"
                @keyup.enter="addTag"
                @blur="addTag"
                @keyup.esc="isAddingTag = false; newTag = ''"
                class="w-24 px-2 py-0.5 text-xs rounded-md bg-white border border-indigo-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none text-slate-700 placeholder-slate-300 transition-all"
                placeholder="Nueva..."
              />
            </div>
          </div>
        </header>

        <!-- Bloques de Contenido -->
        <draggable 
          v-model="draggableBlocks" 
          item-key="id"
          class="flex flex-wrap gap-4 min-h-[500px]"
          group="blocks"
          handle=".drag-handle"
          ghost-class="drag-ghost"
          animation="200"
        >
          <template #item="{ element }">
            <div 
              class="group relative p-4 border transition-all duration-300 min-w-[250px] flex-1 resizable-block bg-white shadow-sm rounded-2xl"
              :class="[
                 store.selectedBlockIds.has(element.id) 
                   ? 'border-indigo-400 shadow-lg ring-4 ring-indigo-50 scale-[1.02] z-30' 
                   : 'border-transparent hover:border-slate-200 hover:bg-slate-50/50'
              ]"
              :style="{ width: element.style?.width || '100%', height: element.style?.height || 'auto' }"
              @mouseup="handleResize(element, $event)"
              @dblclick.stop="store.toggleBlockSelection(element.id)"
            >
              <!-- Menú de Opciones (Transformación) -->
              <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 z-[9999]" v-if="element.type === 'text'">
                <button @click.stop="activeTransformMenu = activeTransformMenu === element.id ? null : element.id" class="p-1.5 text-slate-400 hover:text-slate-600 bg-white/80 backdrop-blur rounded-lg shadow-sm border border-slate-100">
                  <i class="pi pi-ellipsis-v text-sm"></i>
                </button>
                <div v-if="activeTransformMenu === element.id" class="transform-menu-container absolute top-full left-0 mt-2 w-64 rounded-xl shadow-2xl bg-white border border-slate-100 max-h-96 overflow-y-auto p-2">
                  <div v-for="cat in transformOptions" :key="cat.category" class="mb-2">
                    <h4 class="text-[10px] uppercase font-bold text-slate-400 px-2 py-1">{{ cat.category }}</h4>
                    <div 
                      v-for="item in cat.items" 
                      :key="item.label"
                      @click.stop="transformBlock(element, item.type)"
                      class="flex items-center gap-3 px-2 py-1.5 hover:bg-slate-50 rounded-lg cursor-pointer text-slate-700 text-sm"
                    >
                      <i :class="['pi', item.icon, 'text-slate-400 text-xs']"></i>
                      <span>{{ item.label }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Controles del bloque (Arrastrar y Eliminar) -->
              <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 flex items-center gap-2 z-20">
                <!-- Botón Eliminar -->
                <button 
                  @click="store.removeBlock(element.id)"
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
              <BlockRenderer :block="element" class="w-full h-full flex flex-col" />
              <!-- Icono de Redimensionamiento -->
              <div class="absolute bottom-1 right-1 opacity-0 group-hover:opacity-100 pointer-events-none text-slate-300">
                <i class="pi pi-angle-double-down-right text-[10px]"></i>
              </div>
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

    <!-- Gatillo de Apertura del Panel -->
    <transition name="fade">
      <button 
        v-if="!store.isEditionPanelOpen"
        @click="store.toggleEditionPanel()"
        class="fixed top-20 right-0 z-50 flex items-center justify-center w-10 h-10 rounded-l-2xl bg-white/70 backdrop-blur-md border border-r-0 border-slate-200 shadow-lg text-slate-600 hover:text-indigo-600 hover:bg-white transition-all group"
        title="Mostrar Panel de Edición"
      >
        <i class="pi pi-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
      </button>
    </transition>
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
import { computed, watch, ref, nextTick } from 'vue';
import { useCategoryStore, SUBTYPE_SCHEMAS } from '@/stores/categoryStore';
import debounce from 'lodash.debounce';
import draggable from 'vuedraggable';
import EmojiPicker from '@/components/dynamic/EmojiPicker.vue';
import BlockRenderer from '@/components/dynamic/BlockRenderer.vue';
import SlashMenu from '@/components/dynamic/SlashMenu.vue';

const store = useCategoryStore();

const showTitleMenu = ref(false);
const isAddingTag = ref(false);
const newTag = ref('');
const tagInput = ref(null);
const activeTransformMenu = ref(null);

const transformOptions = [
  {
    category: 'Básicos',
    items: [
      { label: 'Texto', icon: 'pi-align-left', type: 'text' },
      { label: 'Número', icon: 'pi-percentage', type: 'number' },
      { label: 'Seleccionar', icon: 'pi-chevron-down', type: 'select' },
      { label: 'Selección múltiple', icon: 'pi-list', type: 'multi-select' },
      { label: 'Estado', icon: 'pi-tag', type: 'status' },
      { label: 'Fecha', icon: 'pi-calendar', type: 'date' },
      { label: 'Personas', icon: 'pi-users', type: 'people' },
      { label: 'Archivos y multimedia', icon: 'pi-file', type: 'file' },
      { label: 'Casilla', icon: 'pi-check-square', type: 'checkbox' },
      { label: 'Lista de tareas', icon: 'pi-list', type: 'checklist' },
      { label: 'URL', icon: 'pi-link', type: 'url' },
      { label: 'Correo electrónico', icon: 'pi-envelope', type: 'email' },
      { label: 'Teléfono', icon: 'pi-phone', type: 'phone' },
      { label: 'Lugar', icon: 'pi-map-marker', type: 'place' },
    ]
  }
];

const transformBlock = (element, subType) => {
  if (subType === 'text') {
    store.resetTextBlock(element.id);
  } else {
    element.subType = subType;
    element.content = JSON.parse(JSON.stringify(SUBTYPE_SCHEMAS[subType] || { text: '' }));
    store.saveActiveSub();
  }
  activeTransformMenu.value = null;
};

const addTag = () => {
  if (newTag.value.trim()) {
    if (!store.activeSub.tags) store.activeSub.tags = [];
    if (!store.activeSub.tags.includes(newTag.value.trim())) {
      store.activeSub.tags.push(newTag.value.trim());
      store.saveActiveSub();
    }
  }
  isAddingTag.value = false;
  newTag.value = '';
};

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

const handleResize = (block, event) => {
  const el = event.currentTarget;
  if (!el) return;
  
  const newWidth = el.style.width;
  const newHeight = el.style.height;

  if (!block.style || typeof block.style !== 'object') {
    block.style = { format: block.style || 'p', width: '100%', height: 'auto' };
  }

  if ((newWidth && block.style.width !== newWidth) || (newHeight && block.style.height !== newHeight)) {
    if (newWidth) block.style.width = newWidth;
    if (newHeight) block.style.height = newHeight;
    store.saveActiveSub();
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