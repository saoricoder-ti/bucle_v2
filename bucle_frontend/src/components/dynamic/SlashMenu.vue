<template>
  <div 
    class="absolute z-50 w-64 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 p-2 animate-in fade-in zoom-in duration-200"
  >
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-50 mb-1">
      <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Insertar Bloque</p>
      <button @click="$emit('close')" class="text-gray-300 hover:text-gray-500"><i class="pi pi-times text-[10px]"></i></button>
    </div>
    
    <div class="space-y-1">
      <button 
        v-for="item in options" 
        :key="item.type"
        @click="selectOption(item.type)"
        class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors group"
      >
        <div class="w-8 h-8 rounded-md bg-gray-50 flex items-center justify-center group-hover:bg-white shadow-sm">
          <i :class="['pi text-xs', item.icon]"></i>
        </div>
        <div class="text-left">
          <p class="font-bold">{{ item.label }}</p>
          <p class="text-[10px] text-gray-400">{{ item.desc }}</p>
        </div>
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  show: Boolean,
  position: Object
});

const emit = defineEmits(['select', 'close']);

const options = [
  { label: 'Texto', type: 'text', icon: 'pi-align-left', desc: 'Escribe con estilo Notion' },
  { label: 'Tabla', type: 'table', icon: 'pi-table', desc: 'Importa CSV o crea manual' },
  { label: 'Mapa', type: 'map', icon: 'pi-map', desc: 'Ubicación de eventos' },
  { label: 'Imagen', type: 'image', icon: 'pi-image', desc: 'Sube fotos o capturas' }
];

const selectOption = (type) => emit('select', type);
</script>