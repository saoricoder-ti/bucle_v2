import apiClient from '../apiClient';

/**
 * Cliente de API para la gestión de Categorías y Subcategorías
 * Nombre oficial unificado: Categorías (antes Entidades).
 */
export const categoriasApi = {
  // Traer categorías para el Sidebar (Banner Izquierdo)
  fetchCategories: () => apiClient.get('/categorias'),
  
  // Traer subcategorías por ID de padre (Dashboard Central)
  fetchSubcategories: (catId) => apiClient.get(`/subcategorias/${catId}`),
  
  // Guardar cambios del Editor (Auto-guardado / Actualización de bloques)
  updateSubcategory: (id, data) => apiClient.put(`/subcategorias/${id}`, data),
  
  // Obtiene el esquema dinámico de la categoría (Conectores)
  getSchema: (catId) => apiClient.get(`/categorias/schema/${catId}`),

  // Registra una nueva categoría genérica
  registrarCategoria: (data) => apiClient.post('/categorias', data),

  // Registra una nueva subcategoría (Evento/Ficha) vinculada a una categoría
  registrarSubcategoria: (payload) => apiClient.post('/subcategorias', payload),

  // NUEVAS FUNCIONES DE GESTIÓN DE CATEGORÍA
  updateCategory: (id, data) => apiClient.put(`/categorias/${id}`, data),
  deleteCategory: (id) => apiClient.delete(`/categorias/${id}`),
  duplicateCategory: (id) => apiClient.post(`/categorias/duplicate/${id}`)
};

export default categoriasApi;