<template>
  <div class="group relative py-1">
    <!-- Bloque de Texto Editable: Escudo de Reactividad Desacoplado -->
    <div v-if="block.type === 'text'" class="relative group/text">
      <!-- Icono lateral para menú -->
      <div 
        @click="showTransformMenu = !showTransformMenu" 
        class="absolute -left-6 top-1/2 -translate-y-1/2 opacity-0 group-hover/text:opacity-100 cursor-pointer text-slate-400 hover:text-slate-600 transition-opacity p-1"
      >
        <i class="pi pi-ellipsis-v text-sm"></i>
      </div>
      
      <!-- Menú Desplegable -->
      <div v-if="showTransformMenu" class="absolute left-0 top-full mt-1 w-64 rounded-xl shadow-2xl bg-white border border-slate-100 z-50 max-h-96 overflow-y-auto p-2">
        <div v-for="cat in transformOptions" :key="cat.category" class="mb-2">
          <h4 class="text-[10px] uppercase font-bold text-slate-400 px-2 py-1">{{ cat.category }}</h4>
          <div 
            v-for="item in cat.items" 
            :key="item.label"
            @click="transformBlock(item.type)"
            class="flex items-center gap-3 px-2 py-1.5 hover:bg-slate-50 rounded-lg cursor-pointer text-slate-700 text-sm"
          >
            <i :class="['pi', item.icon, 'text-slate-400 text-xs']"></i>
            <span>{{ item.label }}</span>
          </div>
        </div>
      </div>

      <!-- El div contenteditable actual -->
      <div 
        ref="editableDiv"
        class="outline-none focus:bg-gray-50/50 p-2 rounded-xl transition-all relative text-left whitespace-pre-wrap break-words ltr text-slate-900"
        :contenteditable="!readOnly && isEditing"
        @input="handleInput"
        @focus="isFocused = true"
        @blur="handleBlur"
        @dblclick="enableEditing"
        :data-placeholder="placeholder"
        :class="[
          block.style === 'h1' ? 'text-4xl font-black tracking-tight' : 
          block.style === 'h2' ? 'text-2xl font-bold' : 
          'text-base leading-relaxed',
          !isEditing ? 'cursor-default' : 'cursor-text'
        ]"
      ></div>
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

    <!-- Bloque de Imagen -->
    <div v-else-if="block.type === 'image'" class="my-4 rounded-3xl overflow-hidden border border-gray-100 shadow-sm transition-transform hover:scale-[1.01] duration-500 relative bg-slate-50">
      <div v-if="!block.content" class="flex flex-col items-center justify-center p-8 cursor-pointer" @click="fileInput.click()">
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
      <div v-else class="relative group">
        <img :src="block.content" class="w-full h-auto object-cover max-h-[500px]" />
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
          <button @click="block.content = ''; store.saveActiveSub();" class="p-1.5 bg-white rounded-lg shadow-sm border border-slate-100 text-slate-400 hover:text-red-500">
            <i class="pi pi-trash text-xs"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Bloque de Casilla (Checkbox) -->
    <div v-else-if="block.type === 'checkbox'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors">
      <input 
        type="checkbox" 
        v-model="block.content.checked" 
        class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer"
        :disabled="readOnly"
        @change="store.saveActiveSub()"
      />
      <div 
        class="outline-none flex-1 text-slate-700"
        :contenteditable="!readOnly"
        @blur="e => { block.content.label = e.target.innerText; store.saveActiveSub(); }"
        :class="{ 'line-through text-slate-400': block.content.checked }"
      >{{ block.content.label || 'Nueva tarea' }}</div>
    </div>

    <!-- Bloque de URL -->
    <div v-else-if="block.type === 'url'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors">
      <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-500">
        <i class="pi pi-link"></i>
      </div>
      <input 
        type="url" 
        v-model="block.content" 
        class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-indigo-600 hover:underline placeholder-slate-300"
        placeholder="https://ejemplo.com"
        :disabled="readOnly"
        @blur="store.saveActiveSub()"
      />
    </div>

    <!-- Bloque de Número -->
    <div v-else-if="block.type === 'number'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors">
      <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500">
        <i class="pi pi-percentage"></i>
      </div>
      <input 
        type="number" 
        v-model="block.content" 
        class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300"
        placeholder="0.00"
        :disabled="readOnly"
        @blur="store.saveActiveSub()"
      />
    </div>

    <!-- Bloque de Fecha -->
    <div v-else-if="block.type === 'date'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors">
      <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500">
        <i class="pi pi-calendar"></i>
      </div>
      <input 
        type="date" 
        v-model="block.content" 
        class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300"
        :disabled="readOnly"
        @change="store.saveActiveSub()"
      />
    </div>

    <!-- Bloque de Correo electrónico -->
    <div v-else-if="block.type === 'email'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors">
      <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
        <i class="pi pi-envelope"></i>
      </div>
      <input 
        type="email" 
        v-model="block.content" 
        class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300"
        placeholder="usuario@ejemplo.com"
        :disabled="readOnly"
        @blur="store.saveActiveSub()"
      />
    </div>

    <!-- Bloque de Teléfono -->
    <div v-else-if="block.type === 'phone'" class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors">
      <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-green-500">
        <i class="pi pi-phone"></i>
      </div>
      <input 
        type="tel" 
        v-model="block.content" 
        class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300"
        placeholder="+1 234 567 890"
        :disabled="readOnly"
        @blur="store.saveActiveSub()"
      />
    </div>

    <!-- Fallback Genérico para tipos no implementados -->
    <div v-else class="flex items-center gap-3 p-2 hover:bg-slate-50/50 rounded-xl transition-colors">
      <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
        <i class="pi pi-cog"></i>
      </div>
      <input 
        type="text" 
        v-model="block.content" 
        class="outline-none flex-1 bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-300"
        :placeholder="`Propiedad ${block.type}...`"
        :disabled="readOnly"
        @blur="store.saveActiveSub()"
      />
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, watch, nextTick } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import TableBlock from './TableBlock.vue';
import MapBlock from './MapBlock.vue';
import CalendarBlock from './CalendarBlock.vue';
import ListBlock from './ListBlock.vue';
import ChecklistBlock from './ChecklistBlock.vue';

const props = defineProps({
  block: { type: Object, required: true },
  readOnly: { type: Boolean, default: false }
});

const store = useCategoryStore();
const editableDiv = ref(null);
const isFocused = ref(false);
const isEditing = ref(false);
const showTransformMenu = ref(false);
const fileInput = ref(null);

const handleImageUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  
  const reader = new FileReader();
  reader.onload = (event) => {
    // eslint-disable-next-line vue/no-mutating-props
    props.block.content = event.target.result; // Base64
    store.saveActiveSub();
  };
  reader.readAsDataURL(file);
};

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

const transformBlock = (type) => {
  if (props.readOnly) return;
  // eslint-disable-next-line vue/no-mutating-props
  props.block.type = type;
  // Inicializar contenido según el tipo
  if (type === 'map') props.block.content = { lat: -0.1807, lng: -78.4678 };
  else if (type === 'table') props.block.content = { columns: ['Col 1', 'Col 2'], rows: [] };
  else if (type === 'calendar') props.block.content = { selectedDate: new Date().toISOString(), events: [] };
  else if (type === 'list') props.block.content = { items: [''] };
  else if (type === 'checklist') props.block.content = { items: [{ checked: false, text: '' }] };
  else if (type === 'checkbox') props.block.content = { checked: false, label: '' };
  else props.block.content = '';
  
  showTransformMenu.value = false;
  store.saveActiveSub();
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
  isEditing.value = false;
  store.saveActiveSub();
};

const placeholder = computed(() => {
  if (props.block.style === 'h1') return 'Título principal...';
  if (props.block.style === 'h2') return 'Subtítulo...';
  return 'Escribe algo...';
});
</script>