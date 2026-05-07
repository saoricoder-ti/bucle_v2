/**
 * Convierte un string Markdown en un arreglo de bloques para la arquitectura Bucle
 */
export const parseMarkdownToBlocks = (mdText) => {
  const lines = mdText.split('\n');
  const blocks = [];

  lines.forEach(line => {
    if (!line.trim()) return;

    // Detectar Títulos
    if (line.startsWith('# ')) {
      blocks.push({ id: Date.now() + Math.random(), type: 'text', content: line.replace('# ', ''), style: 'h1' });
    } 
    // Detectar Listas
    else if (line.startsWith('- ') || line.startsWith('* ')) {
      blocks.push({ id: Date.now() + Math.random(), type: 'text', content: line.substring(2), style: 'bullet' });
    } 
    // Texto plano
    else {
      blocks.push({ id: Date.now() + Math.random(), type: 'text', content: line, style: 'p' });
    }
  });

  return blocks;
};