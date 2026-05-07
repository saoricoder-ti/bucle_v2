import apiClient from '../apiClient';

/**
 * Cliente de API para la gestión de Entidades, Categorías y Subcategorías
 * Sincronizado con la arquitectura Notion-style.
 */
export const entidadesApi = {
  // Traer categorías para el Sidebar (Banner Izquierdo)
  fetchCategories: () => apiClient.get('/categorias'),
  
  // Traer subcategorías por ID de padre (Dashboard Central)
  fetchSubcategories: (catId) => apiClient.get(`/subcategorias/${catId}`),
  
  // Guardar cambios del Editor (Auto-guardado / Actualización de bloques)
  updateSubcategory: (id, data) => apiClient.put(`/subcategorias/${id}`, data),

  // --- Funciones heredadas/compatibilidad ---
  
  // Obtiene el esquema dinámico de la categoría
  getSchema: (category) => apiClient.get(`/config/schema/${category}`),

  // Registra una nueva categoría genérica
  registrarCategoria: (data) => apiClient.post('/categorias', data),

  // Registra una nueva entidad (Mascota, Auto, etc.)
  registrar: (payload) => apiClient.post('/entidades/registrar', payload),

  // Actualiza los bloques de datos extra (Alias de updateSubcategory si fuera necesario)
  updateBlocks: (id, payload) => apiClient.put(`/entidades/update-blocks/${id}`, payload)
};

export default entidadesApi;