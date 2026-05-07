import axios from 'axios';

const apiClient = axios.create({
  // Ajusta la URL según tu entorno de CodeIgniter 4
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  timeout: 10000 // 10 segundos de espera
});

// Interceptor para manejo global de errores (opcional pero recomendado)
apiClient.interceptors.response.use(
  response => response,
  error => {
    console.error('Error en la API:', error.response?.data || error.message);
    return Promise.reject(error);
  }
);

export default apiClient;