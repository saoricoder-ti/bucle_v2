import { ref } from 'vue';
import entidadesApi from '@/api/endpoints/entidades';

export function useBucle() {
  const schema = ref(null);
  const loading = ref(false);
  const error = ref(null);

  /**
   * Carga la configuración dinámica de la categoría seleccionada
   */
  const loadCategory = async (category) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await entidadesApi.getSchema(category);
      schema.value = response.data;
    } catch (err) {
      error.value = 'No se pudo cargar la configuración de la categoría.';
    } finally {
      loading.value = false;
    }
  };

  /**
   * Envía los datos del formulario al backend
   */
  const submitEntity = async (category, identifier, fields) => {
    loading.value = true;
    try {
      await entidadesApi.registrar({
        categoria: category,
        identificador: identifier,
        campos: fields
      });
      return { success: true };
    } catch (err) {
      return { success: false, message: err.message };
    } finally {
      loading.value = false;
    }
  };

  return {
    schema,
    loading,
    error,
    loadCategory,
    submitEntity
  };
}