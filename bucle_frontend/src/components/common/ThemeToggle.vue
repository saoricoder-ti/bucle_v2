<template>
  <!-- border-indigo-500: Borde vibrante cuando el modo oscuro está activo -->
  <!-- shadow-[rgba(99,102,241,0.3)]: Sombra de brillo azul (glow effect) -->
  <button 
    @click="theme.toggleTheme()"
    class="w-8 h-8 rounded-full border-2 transition-all duration-500 flex items-center justify-center group overflow-hidden"
    :class="theme.isDark 
      ? 'border-indigo-500 bg-indigo-500/10 shadow-[0_0_15px_rgba(99,102,241,0.3)]' 
      : 'border-slate-700 bg-transparent hover:border-slate-400'"
    title="Cambiar Tema"
  >
    <transition name="scale-fade" mode="out-in">
      <!-- text-indigo-400: Color lavanda suave para el icono de la luna -->
      <i 
        v-if="theme.isDark" 
        key="moon"
        class="pi pi-moon text-[10px] text-indigo-400"
      ></i>
      <!-- text-slate-500: Gris neutro para el modo claro -->
      <i 
        v-else 
        key="sun"
        class="pi pi-sun text-[10px] text-slate-500"
      ></i>
    </transition>
  </button>
</template>

<script setup>
import { useThemeStore } from '@/stores/themeStore';
const theme = useThemeStore();
</script>
