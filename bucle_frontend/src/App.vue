<template>
  <!-- Contenedor Raíz: Estética Clara Premium (Notion Style) -->
  <div class="h-screen w-screen overflow-hidden flex bg-transparent text-slate-900 font-sans antialiased selection:bg-indigo-100">
    
    <!-- 1. Banner Izquierdo (Sidebar): Navegación Principal -->
    <aside class="w-[280px] flex-shrink-0 z-30 border-r border-gray-100 bg-white flex flex-col shadow-2xl">
      <CategorySidebar />
    </aside>

    <!-- 2. Contenedor de Trabajo: Área Flexible -->
    <div class="flex-1 flex flex-col min-w-0 relative bg-transparent">
      
      <!-- Banner Superior (Header): Contexto y Brújula -->
      <header class="h-16 w-full flex-shrink-0 border-b border-gray-100 flex items-center px-6 z-[60] bg-white shadow-sm gap-4 transition-colors duration-500">
        
        <!-- Botón Volver -->
        <transition name="fade">
          <button 
            v-if="store.view === 'editor'"
            @click="store.goBack"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900 text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-90 group"
            title="Volver al Dashboard"
          >
            <i class="pi pi-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
          </button>
        </transition>

        <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
          <i class="pi pi-home text-[12px] opacity-70"></i>
          <span>Workspace</span> 
          <span class="opacity-30">/</span> 
          <span class="text-indigo-600 font-black">{{ store.activeCategory?.nombre || 'Dashboard' }}</span>
          <transition name="fade">
            <span v-if="store.activeSub" class="flex items-center gap-3">
              <span class="opacity-30">/</span>
              <span class="text-slate-900 truncate max-w-[250px]">{{ store.activeSub.nombre }}</span>
            </span>
          </transition>
        </div>
      </header>

      <!-- Área de Contenido: Scroll Independiente -->
      <div class="flex-1 flex overflow-hidden relative">
        
        <!-- Lienzo Central (Dashboard o Editor) -->
        <main class="flex-1 overflow-y-auto z-10 custom-scrollbar bg-transparent">
          <transition name="fade-slide" mode="out-in">
            <DashboardPrincipal v-if="store.view === 'dashboard'" :key="'dash'" />
            <EditorCanvas v-else-if="store.view === 'editor'" :key="'edit'" />
          </transition>
        </main>

        <!-- 3. Banner Derecho (Panel de Edición) -->
        <transition name="slide-right">
          <aside 
            v-if="store.view === 'editor' && store.activeSub" 
            class="w-[320px] flex-shrink-0 z-20 border-l border-gray-100 bg-white shadow-2xl flex flex-col overflow-hidden"
          >
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
              <EditionPanel />
            </div>
          </aside>
        </transition>

      </div>
    </div>

    <!-- Capas Superiores Globales -->
    <CategoryForm v-if="store.isCreatingCategory" />
    <ImportLoader :active="store.isImporting" />
    <Toast />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import CategorySidebar from '@/components/layout/CategorySidebar.vue';
import DashboardPrincipal from '@/views/DashboardPrincipal.vue';
import EditorCanvas from '@/views/EditorCanvas.vue';
import EditionPanel from '@/components/dynamic/EditionPanel.vue';
import CategoryForm from '@/components/dynamic/CategoryForm.vue';
import ImportLoader from '@/components/dynamic/ImportLoader.vue';
import Toast from '@/components/common/Toast.vue';

const store = useCategoryStore();

onMounted(() => {
  store.initApp();
});
</script>