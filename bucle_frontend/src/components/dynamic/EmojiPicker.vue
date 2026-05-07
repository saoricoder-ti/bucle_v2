<template>
  <div class="relative inline-block">
    <!-- El "Botón" del Emoji que dibujaste -->
    <button 
      @click="showPicker = !showPicker"
      class="text-4xl p-2 hover:bg-gray-100 rounded-2xl transition-all duration-200 grayscale hover:grayscale-0"
    >
      {{ modelValue || '📁' }}
    </button>

    <!-- Selector Flotante -->
    <div v-if="showPicker" class="absolute z-[60] mt-2">
      <div class="fixed inset-0" @click="showPicker = false"></div>
      <EmojiPicker 
        :native="true" 
        @select="onSelectEmoji" 
        class="relative shadow-2xl border-none rounded-2xl"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';

const props = defineProps(['modelValue']);
const emit = defineEmits(['update:modelValue']);
const showPicker = ref(false);

const onSelectEmoji = (emoji) => {
  emit('update:modelValue', emoji.i); // Emite el emoji seleccionado
  showPicker.value = false;
};
</script>