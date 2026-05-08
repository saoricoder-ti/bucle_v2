<template>
  <div class="group relative py-1">
    <!-- Bloque de Texto Editable: Escudo de Reactividad Desacoplado -->
    <div 
      v-if="block.type === 'text'"
      ref="editableDiv"
      class="outline-none focus:bg-gray-50/50 p-2 rounded-xl transition-all relative text-left whitespace-pre-wrap break-words ltr text-slate-900"
      :contenteditable="!readOnly"
      @input="handleInput"
      @focus="isFocused = true"
      @blur="handleBlur"
      :data-placeholder="placeholder"
      :class="[
        block.style === 'h1' ? 'text-4xl font-black tracking-tight' : 
        block.style === 'h2' ? 'text-2xl font-bold' : 
        'text-base leading-relaxed'
      ]"
    ></div>

    <!-- Bloque de Tabla -->
    <TableBlock 
      v-else-if="block.type === 'table'" 
      :block="block" 
      :readOnly="readOnly" 
    />

    <!-- Bloque de Mapa -->
    <MapBlock 
      v-else-if="block.type === 'map'" 
      :block="block" 
      :readOnly="readOnly" 
    />

    <!-- Bloque de Calendario -->
    <CalendarBlock
      v-else-if="block.type === 'calendar'"
      :block="block"
      :readOnly="readOnly"
    />

    <!-- Bloque de Lista -->
    <ListBlock v-else-if="block.type === 'list'" :block="block" :readOnly="readOnly" />

    <!-- Bloque de Imagen -->
    <div v-else-if="block.type === 'image'" class="my-4 rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-transform hover:scale-[1.01] duration-500">
      <img :src="block.content" class="w-full h-auto object-cover max-h-[500px]" />
    </div>

    <!-- Fallback -->
    <div v-else class="p-4 bg-red-50 text-red-500 text-xs rounded-xl border border-red-100">
      Tipo de bloque no soportado: {{ block.type }}
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, watch } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import TableBlock from './TableBlock.vue';
import MapBlock from './MapBlock.vue';
import CalendarBlock from './CalendarBlock.vue';
import ListBlock from './ListBlock.vue';

const props = defineProps({
  block: { type: Object, required: true },
  readOnly: { type: Boolean, default: false }
});

const store = useCategoryStore();
const editableDiv = ref(null);
const isFocused = ref(false);

onMounted(() => {
  if (editableDiv.value) {
    editableDiv.value.innerText = props.block.content || '';
  }
});

watch(() => props.block.content, (newVal) => {
  if (editableDiv.value && !isFocused.value && editableDiv.value.innerText !== newVal) {
    editableDiv.value.innerText = newVal || '';
  }
});

const handleInput = (e) => {
  if (props.readOnly) return;
  // eslint-disable-next-line vue/no-mutating-props
  props.block.content = e.target.innerText;
};

const handleBlur = () => {
  isFocused.value = false;
  store.saveActiveSub();
};

const placeholder = computed(() => {
  if (props.block.style === 'h1') return 'Título principal...';
  if (props.block.style === 'h2') return 'Subtítulo...';
  return 'Escribe algo...';
});
</script>