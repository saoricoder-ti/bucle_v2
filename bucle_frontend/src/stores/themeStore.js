import { defineStore } from 'pinia';

export const useThemeStore = defineStore('theme', {
  state: () => ({
    isDark: localStorage.getItem('theme') === 'dark'
  }),
  actions: {
    /**
     * Alterna entre modo claro y oscuro
     */
    toggleTheme() {
      this.isDark = !this.isDark;
      this.applyTheme();
    },

    /**
     * Inicializa el tema basándose en el estado persistido
     */
    initTheme() {
      this.applyTheme();
    },

    /**
     * Aplica físicamente la clase .dark al elemento raíz
     */
    applyTheme() {
      const html = document.documentElement;
      
      if (this.isDark) {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      }
    }
  }
});
