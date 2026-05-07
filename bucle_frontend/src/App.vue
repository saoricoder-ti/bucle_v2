<template>
  <!-- Contenedor Raíz: Bloqueo de scroll global y gestión de fondo -->
  <!-- bg-[#fbfbfa]: Fondo claro principal (Notion style) -->
  <!-- dark:bg-[#191919]: Fondo oscuro 'Deep Night' solicitado por el usuario -->
  <div class="h-screen w-screen overflow-hidden flex bg-[#fbfbfa] dark:bg-[#191919] text-slate-900 dark:text-slate-100 transition-colors duration-500 font-sans antialiased selection:bg-indigo-100 dark:selection:bg-indigo-900/30">
    
    <!-- 1. Banner Izquierdo (Sidebar): Navegación Principal -->
    <!-- bg-white / dark:bg-slate-950: Colores de fondo del sidebar lateral -->
    <!-- border-gray-100 / dark:border-slate-800: Separación sutil entre paneles -->
    <aside class="w-[280px] flex-shrink-0 z-30 border-r border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-950 flex flex-col shadow-2xl">
      <CategorySidebar />
    </aside>

    <!-- 2. Contenedor de Trabajo: Área Flexible -->
    <!-- Este div es TRANSPARENTE; el color que ves de fondo es el del Contenedor Raíz (Línea 5) -->
    <div class="flex-1 flex flex-col min-w-0 relative bg-transparent">
      
      <!-- Banner Superior (Header): Contexto y Brújula -->
      <!-- bg-white / dark:bg-[#1a1c23]: Fondo del encabezado superior -->
      <header class="h-16 w-full flex-shrink-0 border-b border-gray-100 dark:border-slate-800 flex items-center px-6 z-[60] bg-white dark:bg-[#1a1c23] shadow-sm gap-4 transition-colors duration-500">
        
        <!-- Botón Volver (Resaltado con fondo para máxima visibilidad) -->
        <!-- bg-slate-900: Fondo negro del botón en modo claro -->
        <!-- dark:bg-slate-700: Fondo gris oscuro en modo noche -->
        <transition name="fade">
          <button 
            v-if="store.view === 'editor'"
            @click="store.goBack"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900 dark:bg-slate-700 text-white shadow-xl hover:bg-indigo-600 dark:hover:bg-indigo-500 transition-all active:scale-90 group"
            title="Volver al Dashboard"
          >
            <i class="pi pi-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
          </button>
        </transition>

        <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
          <i class="pi pi-home text-[12px] opacity-70"></i>
          <span>Workspace</span> 
          <span class="opacity-30">/</span> 
          <!-- text-indigo-600: Color de acento para la categoría activa -->
          <span class="text-indigo-600 dark:text-indigo-400 font-black">{{ store.activeCategory?.nombre || 'Dashboard' }}</span>
          <transition name="fade">
            <span v-if="store.activeSub" class="flex items-center gap-3">
              <span class="opacity-30">/</span>
              <span class="text-slate-900 dark:text-slate-200 truncate max-w-[250px]">{{ store.activeSub.nombre }}</span>
            </span>
          </transition>
        </div>
      </header>

      <!-- Área de Contenido: Scroll Independiente -->
      <div class="flex-1 flex overflow-hidden relative">
        
        <!-- Lienzo Central (Dashboard o Editor): Siempre Transparente -->
        <main class="flex-1 overflow-y-auto z-10 custom-scrollbar bg-transparent">
          <transition name="fade-slide" mode="out-in">
            <DashboardPrincipal v-if="store.view === 'dashboard'" :key="'dash'" />
            <EditorCanvas v-else-if="store.view === 'editor'" :key="'edit'" />
          </transition>
        </main>

        <!-- 3. Banner Derecho (Panel de Edición): Solo en modo Editor -->
        <transition name="slide-right">
          <aside 
            v-if="store.view === 'editor' && store.activeSub" 
            class="w-[320px] flex-shrink-0 z-20 border-l border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl flex flex-col overflow-hidden"
          >
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
              <EditionPanel />
            </div>
          </aside>
        </transition>

      </div>
    </div>

    <!-- Capas Superiores Globales (Notificaciones, Loaders y Formularios) -->
    <CategoryForm v-if="store.isCreatingCategory" />
    <ImportLoader :active="store.isImporting" />
    <Toast />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';
import { useThemeStore } from '@/stores/themeStore';
import CategorySidebar from '@/components/layout/CategorySidebar.vue';
import DashboardPrincipal from '@/views/DashboardPrincipal.vue';
import EditorCanvas from '@/views/EditorCanvas.vue';
import EditionPanel from '@/components/dynamic/EditionPanel.vue';
import CategoryForm from '@/components/dynamic/CategoryForm.vue';
import ImportLoader from '@/components/dynamic/ImportLoader.vue';
import Toast from '@/components/common/Toast.vue';

const store = useCategoryStore();
const themeStore = useThemeStore();

onMounted(() => {
  store.initApp();
  themeStore.initTheme();
});
</script>