<template>
  <!-- ═══════════════════════════════════════════════════
       Matriz de Bucle v1.2 — Full-Width Rails Layout
       Header y Footer cubren 100% del ancho.
       Sidebar, Canvas y Panel viven en el contenedor central.
       ═══════════════════════════════════════════════════ -->
  <div class="h-screen w-screen flex flex-col overflow-hidden bg-transparent text-slate-900 font-sans antialiased selection:bg-indigo-100">
    
    <!-- ┌──────────────────────────────────────────────┐ -->
    <!-- │  1. PANEL SUPERIOR — Full-Width Header       │ -->
    <!-- └──────────────────────────────────────────────┘ -->
    <TheHeader />

    <!-- ┌──────────┬───────────────────────┬───────────┐ -->
    <!-- │ SIDEBAR  │   CANVAS / BODY       │  PANEL    │ -->
    <!-- │          │                       │  EDICIÓN  │ -->
    <!-- └──────────┴───────────────────────┴───────────┘ -->
    <div class="flex-1 flex overflow-hidden">
      
      <!-- 2. PANEL IZQUIERDO — Sidebar de Navegación -->
      <aside class="w-[var(--sidebar-width)] flex-shrink-0 z-30 border-r border-gray-100 bg-white flex flex-col shadow-2xl">
        <CategorySidebar />
      </aside>

      <!-- 3. LIENZO CENTRAL — Dashboard o Editor -->
      <main class="flex-1 overflow-y-auto z-10 custom-scrollbar bg-transparent">
        <transition name="fade-slide" mode="out-in">
          <DashboardBienvenida v-if="!store.activeCategory && !store.loading" :key="'welcome'" />
          <DashboardPrincipal v-else-if="store.view === 'dashboard'" :key="'dash'" />
          <EditorCanvas v-else-if="store.view === 'editor'" :key="'edit'" />
        </transition>
      </main>

      <!-- 4. PANEL DERECHO — Herramientas de Edición (sin reflow) -->
      <aside 
        class="flex-shrink-0 z-20 overflow-hidden panel-transition"
        :style="{ width: showRightPanel ? 'var(--panel-width)' : '0px' }"
      >
        <div class="w-[var(--panel-width)] h-full border-l border-gray-100 bg-white shadow-2xl flex flex-col">
          <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <EditionPanel />
          </div>
        </div>
      </aside>

    </div>

    <!-- ┌──────────────────────────────────────────────┐ -->
    <!-- │  5. PANEL INFERIOR — Full-Width StatusBar     │ -->
    <!-- └──────────────────────────────────────────────┘ -->
    <TheStatusBar />

    <!-- Capas Superiores Globales (Modales / Overlays) -->
    <CategoryForm v-if="store.isCreatingCategory" />
    <ImportLoader :active="store.isImporting" />
    <Toast />
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useCategoryStore } from '@/stores/categoryStore';

// Layout Components
import TheHeader from '@/components/layout/TheHeader.vue';
import TheStatusBar from '@/components/layout/TheStatusBar.vue';
import CategorySidebar from '@/components/layout/CategorySidebar.vue';

// Views
import DashboardPrincipal from '@/views/DashboardPrincipal.vue';
import DashboardBienvenida from '@/views/DashboardBienvenida.vue';
import EditorCanvas from '@/views/EditorCanvas.vue';

// Dynamic Components
import EditionPanel from '@/components/dynamic/EditionPanel.vue';
import CategoryForm from '@/components/dynamic/CategoryForm.vue';
import ImportLoader from '@/components/dynamic/ImportLoader.vue';
import Toast from '@/components/common/Toast.vue';

const store = useCategoryStore();
const route = useRoute();

/**
 * Controla la visibilidad del panel derecho.
 * Se anima via CSS width transition (sin v-if) para evitar reflow del canvas.
 */
const showRightPanel = computed(() => {
  return store.isEditionPanelOpen && store.view === 'editor' && store.activeSub;
});

watch(() => route.params, (newParams) => {
  store.syncFromRoute(newParams.categoryName, newParams.subName);
}, { immediate: true });

onMounted(() => {
  if (store.categories.length === 0) {
    store.initApp().then(() => {
      store.syncFromRoute(route.params.categoryName, route.params.subName);
    });
  }
});
</script>

<style scoped>
.panel-transition {
  transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>