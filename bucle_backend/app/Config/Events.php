<?php

namespace Config;

use CodeIgniter\Events\Events;

// Definimos el evento 'entity_created'
Events::on('entity_created', function ($data) {
    // Aquí llamamos a la clase que se encargará del procesamiento
    \App\Events\EntityEvents::runScrapers($data);
});