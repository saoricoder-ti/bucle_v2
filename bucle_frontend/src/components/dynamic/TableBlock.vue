<template>
  <div class="my-6 border border-gray-100 rounded-3xl bg-white shadow-sm overflow-hidden">
    <!-- Estado: Tabla con datos -->
    <div v-if="block.content && block.content.rows && block.content.rows.length > 0" class="overflow-x-auto">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50/50 border-b border-gray-100">
          <tr>
            <th v-for="col in block.content.columns" :key="col" class="px-6 py-4 font-black text-[10px] uppercase tracking-widest text-gray-400">
              {{ col }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="(row, i) in block.content.rows" :key="i" class="hover:bg-gray-50/50 transition-colors">
            <td v-for="col in block.content.columns" :key="col" class="px-6 py-4 text-gray-600 font-medium">
              {{ row[col] }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Estado: Vacío / Importar CSV (Solo lectura falsa) -->
    <div v-else-if="!readOnly" class="p-10 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 rounded-3xl m-2 bg-gray-50/30">
      <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4">
        <i class="pi pi-table text-indigo-500 text-xl"></i>
      </div>
      <h4 class="text-sm font-bold text-gray-800 mb-1">Importar Datos</h4>
      <p class="text-xs text-gray-400 mb-6">Carga un archivo .csv para generar la tabla automáticamente</p>
      
      <label class="bg-gray-900 text-white px-6 py-3 rounded-2xl text-xs font-bold cursor-pointer hover:bg-black transition-all">
        <span>Seleccionar Archivo</span>
        <input type="file" accept=".csv" class="hidden" @change="handleFileUpload" />
      </label>
    </div>

    <!-- Estado: Vacío en modo lectura -->
    <div v-else class="p-8 text-center text-gray-300 italic text-sm">
      Tabla sin datos
    </div>
  </div>
</template>

<script setup>
import { parseCsv } from '@/services/CsvService';

const props = defineProps({
  block: Object,
  readOnly: Boolean
});

const handleFileUpload = async (e) => {
  const file = e.target.files[0];
  if (!file) return;

  try {
    const data = await parseCsv(file);
    // Actualizamos el contenido del bloque con las columnas y filas parseadas
    props.block.content = data;
  } catch (error) {
    alert('Error al procesar el archivo CSV. Asegúrate de que el formato sea correcto.');
    console.error(error);
  }
};
</script>
