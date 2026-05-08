<template>
  <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm transition-all hover:shadow-md">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
        <i class="pi pi-list text-indigo-500 text-xs"></i>
      </div>
      <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lista Dinámica</span>
    </div>

    <ul class="space-y-3">
      <li 
        v-for="(item, index) in block.content.items" :key="index"
        class="flex items-start gap-3 group"
      >
        <div class="mt-2 w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0 transition-transform group-hover:scale-150"></div>
        
        <div 
          class="flex-1 outline-none text-slate-700 text-sm leading-relaxed min-h-[1.5em] empty:before:content-[attr(data-placeholder)] empty:before:text-slate-300"
          :contenteditable="!readOnly"
          @input="updateItem($event, index)"
          @keydown.enter.prevent="addItem(index)"
          @keydown.backspace="handleBackspace($event, index)"
          @blur="store.saveActiveSub()"
          ref="itemRefs"
          data-placeholder="Escribe un elemento..."
        >{{ item }}</div>
      </li>
    </ul>

    <div v-if="!readOnly" class="mt-4 pt-4 border-t border-slate-50">
      <button 
        @click="addItem(block.content.items.length - 1)"
        class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:underline flex items-center gap-2"
      >
        <i class="pi pi-plus text-[8px]"></i>
        Añadir Elemento
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';

const props = defineProps({
  block: Object,
  readOnly: Boolean
});

const store = useCategoryStore();
const itemRefs = ref([]);

const updateItem = (e, index) => {
  // eslint-disable-next-line vue/no-mutating-props
  props.block.content.items[index] = e.target.innerText;
};

const addItem = async (index) => {
  if (props.readOnly) return;
  // eslint-disable-next-line vue/no-mutating-props
  props.block.content.items.splice(index + 1, 0, '');
  await nextTick();
  itemRefs.value[index + 1]?.focus();
  store.saveActiveSub();
};

const handleBackspace = async (e, index) => {
  if (props.readOnly) return;
  
  const content = e.target.innerText;
  if (content === '' && props.block.content.items.length > 1) {
    e.preventDefault();
    // eslint-disable-next-line vue/no-mutating-props
    props.block.content.items.splice(index, 1);
    await nextTick();
    const prevIndex = index > 0 ? index - 1 : 0;
    itemRefs.value[prevIndex]?.focus();
    
    // Posicionar cursor al final
    const range = document.createRange();
    const sel = window.getSelection();
    range.selectNodeContents(itemRefs.value[prevIndex]);
    range.collapse(false);
    sel.removeAllRanges();
    sel.addRange(range);
    
    store.saveActiveSub();
  }
};
</script>
