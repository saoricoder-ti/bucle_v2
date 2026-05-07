<?php
$host = "db.fvanqsugyxtekbnenuis.supabase.co";
$port = "6543";
$dbname = "postgres";
$user = "postgres";
$pass = "supabasebucle";

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$pass sslmode=require";
echo "Intentando conectar a $host:$port...\n";
$dbconn = pg_connect($connection_string);

if($dbconn) {
    echo "¡CONEXIÓN EXITOSA!\n";
    pg_close($dbconn);
} else {
    echo "ERROR DE CONEXIÓN.\n";
}
