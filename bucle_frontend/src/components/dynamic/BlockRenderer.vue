<template>
  <div class="group relative py-1">
    <!-- Bloque de Texto Editable: Escudo de Reactividad Desacoplado -->
    <div v-if="block.type === 'text'" class="relative group/text flex flex-wrap items-center">


      <!-- El div contenteditable actual -->
      <div 
        v-if="!block.subType || block.subType === 'text'"
        ref="editableDiv"
        class="outline-none focus:bg-gray-50/50 p-2 rounded-xl transition-all relative text-left whitespace-pre-wrap break-words ltr text-slate-900 w-full"
        :contenteditable="!readOnly && isEditing"
        @input="handleInput"
        @focus="isFocused = true"
        @blur="handleBlur"
        @dblclick="enableEditing"
        :data-placeholder="placeholder"
        :class="[
          (block.style?.format === 'h1' || block.style === 'h1') ? 'text-4xl font-black tracking-tight' : 
          (block.style?.format === 'h2' || block.style === 'h2') ? 'text-2xl font-bold' : 
          'text-base leading-relaxed',
          !isEditing ? 'cursor-default' : 'cursor-text'
        ]"
      ></div>

      <!-- SubType: number -->
      <div v-else-if="block.subType === 'number'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500 shrink-0">
          <i class="pi pi-percentage"></i>
        </div>
        <input 
          type="number" 
          v-model="block.content.value" 
          class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300 font-medium"
          placeholder="0.00"
          :disabled="readOnly"
          @blur="store.saveActiveSub()"
        />
      </div>

      <!-- SubType: url -->
      <div v-else-if="block.subType === 'url'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-500 shrink-0">
          <i class="pi pi-link"></i>
        </div>
        <input 
          type="url" 
          v-model="block.content.url" 
          class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-indigo-600 hover:underline placeholder-slate-300 font-medium"
          placeholder="https://ejemplo.com"
          :disabled="readOnly"
          @blur="store.saveActiveSub()"
        />
      </div>

      <!-- SubType: email -->
      <div v-else-if="block.subType === 'email'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 shrink-0">
          <i class="pi pi-envelope"></i>
        </div>
        <input 
          type="email" 
          v-model="block.content.email" 
          class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300 font-medium"
          placeholder="usuario@ejemplo.com"
          :disabled="readOnly"
          @blur="store.saveActiveSub()"
        />
      </div>

      <!-- SubType: phone -->
      <div v-else-if="block.subType === 'phone'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-green-500 shrink-0">
          <i class="pi pi-phone"></i>
        </div>
        <input 
          type="tel" 
          v-model="block.content.phone" 
          class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300 font-medium"
          placeholder="+1 234 567 890"
          :disabled="readOnly"
          @blur="store.saveActiveSub()"
        />
      </div>

      <!-- SubType: date -->
      <div v-else-if="block.subType === 'date'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 shrink-0">
          <i class="pi pi-calendar"></i>
        </div>
        <input 
          type="date" 
          v-model="block.content.date" 
          class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300 font-medium"
          :disabled="readOnly"
          @change="store.saveActiveSub()"
        />
      </div>

      <!-- SubType: checkbox -->
      <div v-else-if="block.subType === 'checkbox'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <input 
          type="checkbox" 
          v-model="block.content.checked" 
          class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer shrink-0"
          :disabled="readOnly"
          @change="store.saveActiveSub()"
        />
        <div class="text-slate-700 text-sm font-medium">Casilla de verificación</div>
      </div>
      
      <!-- SubType: select -->
      <div v-else-if="block.subType === 'select'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500 shrink-0">
          <i class="pi pi-chevron-down"></i>
        </div>
        <select 
          v-model="block.content.selected" 
          class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 font-medium cursor-pointer"
          :disabled="readOnly"
          @change="store.saveActiveSub()"
        >
          <option value="" disabled selected>Selecciona una opción...</option>
          <option value="Opción 1">Opción 1</option>
          <option value="Opción 2">Opción 2</option>
          <option value="Opción 3">Opción 3</option>
        </select>
      </div>

      <!-- Fallback genérico de subType -->
      <div v-else class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors w-full group">
        <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 shrink-0">
          <i class="pi pi-cog"></i>
        </div>
        <div class="text-slate-500 text-sm italic font-medium">Propiedad tipo "{{ block.subType }}" configurada.</div>
      </div>
    </div>

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

    <!-- Bloque de Checklist -->
    <ChecklistBlock v-else-if="block.type === 'checklist'" :block="block" :readOnly="readOnly" />

    <!-- Bloque de Ciclo -->
    <CycleBlock v-else-if="block.type === 'cycle'" :block="block" />

    <!-- Bloque de Recordatorio -->
    <ReminderBlock v-else-if="block.type === 'reminder'" :block="block" />

    <!-- Bloque de Imagen -->
    <div v-else-if="block.type === 'image'" class="my-4 rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-transform hover:scale-[1.01] duration-500 relative bg-slate-50 w-full h-full flex flex-col items-center justify-center">
      <div v-if="!block.content.url" class="flex flex-col items-center justify-center p-8 cursor-pointer w-full h-full min-h-[150px]" @click="fileInput.click()">
        <i class="pi pi-image text-3xl text-slate-400 mb-2"></i>
        <p class="text-sm text-slate-500">Haz clic para subir una imagen</p>
        <input 
          type="file" 
          ref="fileInput" 
          class="hidden" 
          accept="image/*" 
          @change="handleImageUpload" 
        />
      </div>
      <div v-else class="relative group w-full h-full flex items-center justify-center">
        <img :src="block.content.url" class="w-full h-full object-contain" />
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
          <button @click="block.content.url = ''; store.saveActiveSub();" class="p-1.5 bg-white rounded-lg shadow-sm border border-slate-100 text-slate-400 hover:text-red-500">
            <i class="pi pi-trash text-xs"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Bloque de Frame -->
    <FrameBlock v-else-if="block.type === 'frame'" :block="block" :readOnly="readOnly" />

  </div>
</template>

<script setup>
import { computed, ref, onMounted, watch, nextTick } from 'vue';
import { useCategoryStore, SUBTYPE_SCHEMAS } from '@/stores/categoryStore';
import TableBlock from './TableBlock.vue';
import MapBlock from './MapBlock.vue';
import CalendarBlock from './CalendarBlock.vue';
import ListBlock from './ListBlock.vue';
import ChecklistBlock from './ChecklistBlock.vue';
import CycleBlock from './CycleBlock.vue';
import ReminderBlock from './ReminderBlock.vue';
import FrameBlock from './FrameBlock.vue';

const props = defineProps({
  block: { type: Object, required: true },
  readOnly: { type: Boolean, default: false }
});

const store = useCategoryStore();
const editableDiv = ref(null);
const isFocused = ref(false);
const isEditing = ref(false);
const fileInput = ref(null);

const handleImageUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  
  const reader = new FileReader();
  reader.onload = (event) => {
    // eslint-disable-next-line vue/no-mutating-props
    props.block.content.url = event.target.result; // Base64
    store.saveActiveSub();
  };
  reader.readAsDataURL(file);
};

const enableEditing = () => {
  if (props.readOnly) return;
  isEditing.value = true;
  nextTick(() => {
    if (editableDiv.value) {
      editableDiv.value.focus();
    }
  });
};

onMounted(() => {
  if (editableDiv.value) {
    editableDiv.value.innerText = props.block.content?.text || '';
  }
});

watch(() => props.block.content?.text, (newVal) => {
  if (editableDiv.value && !isFocused.value && editableDiv.value.innerText !== newVal) {
    editableDiv.value.innerText = newVal || '';
  }
});

watch(() => props.block.type, (newType, oldType) => {
  if (newType !== oldType && newType) {
    store.resetBlockContent(props.block);
  }
});

const handleInput = (e) => {
  if (props.readOnly) return;
  // eslint-disable-next-line vue/no-mutating-props
  props.block.content.text = e.target.innerText;
};

const handleBlur = () => {
  isFocused.value = false;
  isEditing.value = false;
  store.saveActiveSub();
};

const placeholder = computed(() => {
  const format = props.block.style?.format || props.block.style;
  if (format === 'h1') return 'Título principal...';
  if (format === 'h2') return 'Subtítulo...';
  return 'Escribe algo...';
});
</script>