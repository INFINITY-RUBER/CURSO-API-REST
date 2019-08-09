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

// levantamos el ID del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '' ; //  un IF inmediato

// Generamos la respuesta asumiendo que el pedido es correcto
switch( strtoupper($_SERVER['REQUEST_METHOD'])) {
    case'GET':
        if (empty($resourceId)) {
            echo json_encode($books);
            
        } else {
            if ( array_key_exists($resourceId, $books)) {
                echo json_encode($books[ $resourceId]);
            }
        }
        
        break;
    case'POST':
    // tomamos la entreada 'cruda'
        $json = file_get_contents('php://input');
        // tranformamos el json en un nuevo elento del arreglo
        $books[] = json_decode( $json, true );
        // Emitimos hacia la slaida ultima clave del arreglo
        echo array_keys( $books)[ count($books) - 1];
        // echo json_encode($books);
        break;
    case'PUT':
        // validamos que el recurso buscado exista
        if ( !empty($resourceId) && array_key_exists( $resourceId, $books)) {
            // tomamos la entreada 'cruda'
            $json = file_get_contents('php://input');
             // tranformamos el json en un nuevo elento del arreglo
            $books[$resourceId] = json_decode( $json, true );
            // retornamos la coleccion modificada en jsom
            echo json_encode($books);
        }
        break;
    case'DELETE':
        // Validando que el recurso exista
        if ( !empty($resourceId) && array_key_exists( $resourceId, $books)) {
            // eliminamos el recurso
            unset( $books[ $resourceId]);
        }
        echo json_encode($books);

        break;
}


// Inicio el servidor en la terminal 1
// php -S localhost:8000 server.php
// GET:
// Terminal 2 ejecutar 
// curl http://localhost:8000 -v
// curl http://localhost:8000?resource_type=books
// curl http://localhost:8000?resource_type=books | jq
//$ curl "http://localhost:8000?resource_type=books&resource_id=1"

// con la expresion regurla en router.php
//curl http://localhost:8000/books/1

// POST:
// curl -X 'POST' http://localhost:8000/books -d "{"titulo": "nuevo libro", "id_autor": 1, "id_genero": 2 }"
// curl -X 'POST' http://localhost:8000/books -d '{'titulo': nuevo libro, "id_autor": 1, "id_genero": 2 }'
// PUT:
// curl -X 'PUT' http://localhost:8000/books/1 -d '{"titulo": "Nuevo Libro", "id_autor": 1, "id_genero": 2}'
// curl -X 'POST' http://localhost:8000/books -d '{'titulo': nuevo libro, "id_autor": 1, "id_genero": 2 }'
// DELETE:
// curl -X 'DELETE' http://localhost:8000/books/1