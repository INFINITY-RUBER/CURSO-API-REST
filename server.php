<?php
// CLASE 06 - Exponer datos a traves de HTTP GET

// Definimos los recursos disponibles
$allowedResourceType = [
    'books',
    'authors',
    'genres',
];

// Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];

if ( !in_array($resourceType, $allowedResourceType)) {
    die;
}

// Defino los recursos
$books = [
    1 => [
        'titulo' => 'Lo que el viento se llevo',
        'id_autor' => 2,
        'id_genero' => 2,
    ],
    2 => [
        'titulo' => 'La Iliada',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
    3 => [
        'titulo' => 'La Odisea',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
];

// Se indica al cliente que lo que recibirá es un json
header('Content-Type: application/json');

// Generamos la respuesta asumiendo que el pedido es correcto
switch( strtoupper($_SERVER['REQUEST_METHOD'])) {
    case'GET':
        echo json_encode($books);
        break;
    case'POST':
        break;
    case'PUT':
        break;
    case'DELETE':
        break;
}


// Inicio el servidor en la terminal 1
// php -S localhost:8000 Server.php

// Terminal 2 ejecutar 
// curl http://localhost:8000 -v
// curl http://localhost:8000?resource_type=books
// curl http://localhost:8000?resource_type=books | jq

//$ curl "http://localhost:8000?resource_type=books&resource_id=1"