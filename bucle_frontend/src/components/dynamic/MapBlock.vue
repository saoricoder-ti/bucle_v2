<template>
  <div class="my-6 group relative">
    <!-- Buscador de ubicación por intuición -->
    <div class="absolute top-4 left-12 z-[400] flex gap-2">
      <input 
        v-model="searchQuery" 
        @keyup.enter="searchLocation"
        placeholder="Buscar dirección..." 
        class="text-xs p-2 rounded-lg border shadow-lg focus:ring-2 focus:ring-indigo-500 outline-none w-64"
      />
    </div>

    <div :id="'map-' + block.id" class="h-80 w-full rounded-2xl border-2 border-gray-100 shadow-inner z-10"></div>
    
    <p class="text-[10px] text-gray-400 mt-2 italic flex items-center gap-1">
      <i class="pi pi-map-marker"></i>
      Coordenadas: {{ block.content.lat.toFixed(4) }}, {{ block.content.lng.toFixed(4) }}
    </p>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps(['block']);
const searchQuery = ref('');
let map = null;
let marker = null;

onMounted(() => {
  // Inicializar mapa centrado en la ubicación guardada o en Ecuador por defecto
  const { lat, lng } = props.block.content;
  map = L.map(`map-${props.block.id}`).setView([lat || -0.1807, lng || -78.4678], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  marker = L.marker([lat || -0.1807, lng || -78.4678], { draggable: true }).addTo(map);

  // Guardar nuevas coordenadas al arrastrar el marcador por intuición
  marker.on('dragend', () => {
    const pos = marker.getLatLng();
    props.block.content.lat = pos.lat;
    props.block.content.lng = pos.lng;
  });
});

const searchLocation = async () => {
  const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${searchQuery.value}`);
  const data = await res.json();
  if (data.length > 0) {
    const { lat, lon } = data[0];
    const newPos = [parseFloat(lat), parseFloat(lon)];
    map.setView(newPos, 15);
    marker.setLatLng(newPos);
    props.block.content.lat = newPos[0];
    props.block.content.lng = newPos[1];
  }
};
</script>