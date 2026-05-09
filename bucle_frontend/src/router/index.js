import { createRouter, createWebHistory } from 'vue-router';

const DummyComponent = { render: () => null };

const routes = [
  { path: '/', redirect: '/workspace' },
  { path: '/workspace', name: 'workspace-home', component: DummyComponent },
  { path: '/workspace/:categoryName/:subCategoryName?', name: 'workspace', component: DummyComponent }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;