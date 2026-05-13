<template>
  <footer class="h-[var(--footer-height)] w-full flex-shrink-0 border-t border-gray-100 bg-white/80 backdrop-blur-sm flex items-center px-6 z-[60]">
    <!-- Izquierda: Estado de Guardado -->
    <div class="flex items-center gap-2 flex-1 min-w-0">
      <div 
        class="w-1.5 h-1.5 rounded-full shrink-0 transition-colors duration-300"
        :class="saveStatusClass"
      ></div>
      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest truncate">
        {{ saveStatusText }}
      </span>
    </div>

    <!-- Centro: Contador de Bloques -->
    <div class="flex items-center gap-2" v-if="store.activeSub">
      <i class="pi pi-th-large text-[9px] text-slate-300"></i>
      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
        {{ blockCount }} bloque{{ blockCount !== 1 ? 's' : '' }}
      </span>
    </div>

    <!-- Derecha: Última Sincronización -->
    <div class="flex items-center gap-2 flex-1 justify-end min-w-0">
      <i v-if="store.lastSavedAt" class="pi pi-cloud-upload text-[9px] text-emerald-400"></i>
      <span class="text-[9px] font-medium text-slate-300 tabular-nums truncate">
        {{ lastSavedDisplay }}
      </span>
    </div>
  </footer>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useCategoryStore } from '@/stores/categoryStore';

const store = useCategoryStore();
const now = ref(Date.now());
let interval = null;

onMounted(() => {
  interval = setInterval(() => { now.value = Date.now(); }, 10000);
});
onUnmounted(() => { clearInterval(interval); });

/**
 * Cuenta recursiva de bloques (excluye main-title, cuenta hijos de frames)
 */
const blockCount = computed(() => {
  if (!store.activeSub?.blocks) return 0;
  const countDeep = (blocks) => {
    let count = 0;
    for (const b of blocks) {
      if (b.role === 'main-title') continue;
      count++;
      if (b.type === 'frame' && b.content?.blocks) {
        count += countDeep(b.content.blocks);
      }
    }
    return count;
  };
  return countDeep(store.activeSub.blocks);
});

const saveStatusClass = computed(() => {
  if (store.isSaving) return 'bg-amber-400 animate-pulse';
  if (store.lastSavedAt) return 'bg-emerald-400';
  return 'bg-slate-300';
});

const saveStatusText = computed(() => {
  if (store.isSaving) return 'Guardando...';
  if (store.lastSavedAt) return 'Sincronizado';
  return 'Listo';
});

const lastSavedDisplay = computed(() => {
  if (!store.lastSavedAt) return '';
  const diff = Math.floor((now.value - store.lastSavedAt) / 1000);
  if (diff < 5) return 'Ahora mismo';
  if (diff < 60) return `Hace ${diff}s`;
  if (diff < 3600) return `Hace ${Math.floor(diff / 60)}min`;
  return new Date(store.lastSavedAt).toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });
});
</script>
