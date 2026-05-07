import Papa from 'papaparse';

export const parseCsv = (file) => {
  return new Promise((resolve, reject) => {
    Papa.parse(file, {
      header: true,         // Usa la primera fila como nombres de columna
      skipEmptyLines: true,
      complete: (results) => {
        // Retornamos un objeto listo para el bloque de tabla
        resolve({
          columns: results.meta.fields,
          rows: results.data
        });
      },
      error: (error) => reject(error)
    });
  });
};