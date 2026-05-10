import { createRouter, createWebHistory } from 'vue-router';
import { useCategoryStore } from '@/stores/categoryStore';

const DummyComponent = { render: () => null };

const routes = [
  { path: '/', redirect: '/workspace' },
  { path: '/workspace/:categoryName?/:subName?', name: 'workspace', component: DummyComponent }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {
  if (to.name === 'workspace' && to.params.categoryName) {
    const store = useCategoryStore();
    if (store.categories.length === 0) {
      await store.initApp();
    }
    const cat = store.categories.find(c => c.nombre.toLowerCase() === to.params.categoryName.toLowerCase());
    if (!cat) {
      return next('/workspace');
    }
  }
  next();
});

export default router;