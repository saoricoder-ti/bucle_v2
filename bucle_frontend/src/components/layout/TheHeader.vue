<template>
  <header class="h-[var(--header-height)] w-full flex-shrink-0 border-b border-gray-100 flex items-center px-6 z-[60] bg-white shadow-sm gap-4 transition-colors duration-500">
    <!-- Botón Volver -->
    <transition name="fade">
      <button 
        v-if="store.view === 'editor'"
        @click="store.goBack()"
        class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900 text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-90 group"
        title="Volver al Dashboard"
      >
        <i class="pi pi-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
      </button>
    </transition>

    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
      <i class="pi pi-home text-[12px] opacity-70"></i>
      <router-link :to="{ name: 'workspace' }" @click="store.resetToWelcome()" class="cursor-pointer hover:text-indigo-600 transition-colors">Bucle</router-link> 
      <span v-if="route.params.categoryName" class="opacity-30">/</span> 
      <span v-if="route.params.categoryName" class="text-indigo-600 font-black">{{ route.params.categoryName }}</span>
      <transition name="fade">
        <span v-if="route.params.subName" class="flex items-center gap-3">
          <span class="opacity-30">/</span>
          <span class="text-slate-900 truncate max-w-[250px] capitalize">{{ route.params.subName }}</span>
        </span>
      </transition>
    </div>
  </header>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { useCategoryStore } from '@/stores/categoryStore';

const store = useCategoryStore();
const route = useRoute();
</script>
