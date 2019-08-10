<?php
// 
//$user = array_key_exists('PHP_AUTH__USER', $_SERVER) ? $_SERVER['PHP_AUTH__USER'] : '';
//$pwd = array_key_exists('PHP_AUTH__PW', $_SERVER) ? $_SERVER['PHP_AUTH__PW'] : '';

// Se indica al cliente que lo que recibirá es un json
header( 'Content-Type: application/json' );

if ( !array_key_exists( 'HTTP_X_TOKEN', $_SERVER ) ) {
	die;
}

$url = 'https://'.$_SERVER['HTTP_HOST'].'/auth';

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_HTTPHEADER, [
	"X-Token: {$_SERVER['HTTP_X_TOKEN']}",
]);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$ret = curl_exec( $ch );

if ( curl_errno($ch) != 0 ) {
	die ( curl_error($ch) );
}

if ( $ret !== 'true' ) {
	http_response_code( 403 );

	die;
}

// Definimos los recursos disponibles
$allowedResourceType = [
    'books',
    'authors',
    'genres',
];

// Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];

if ( !in_array( $resourceType, $allowedResourceTypes ) ) {
	http_response_code( 400 );
	echo json_encode(
		[
			'error' => "$resourceType is un unkown",
		]
	);
	
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



// levantamos el ID del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) 
? $_GET['resource_id'] : '' ; //  un IF inmediato

$method = $_SERVER['REQUEST_METHOD'];

// Generamos la respuesta asumiendo que el pedido es correcto
switch( strtoupper( $method ) ) {
    case 'GET':
		if ( "books" !== $resourceType ) {
			http_response_code( 404 );

			echo json_encode(
				[
					'error' => $resourceType.' not yet implemented :(',
				]
			);

			die;
		}

		if ( !empty( $resourceId ) ) {
			if ( array_key_exists( $resourceId, $books ) ) {
				echo json_encode(
					$books[ $resourceId ]
				);
			} else {
				http_response_code( 404 );

				echo json_encode(
					[
						'error' => 'Book '.$resourceId.' not found :(',
					]
				);
			}
		} else {
			echo json_encode(
				$books
			);
		}

		die;
		
		break;
        // tomamos la entreada 'cruda'
    case 'POST':
		$json = file_get_contents( 'php://input' );
        // tranformamos el json en un nuevo elento del arreglo
		$books[] = json_decode( $json );
        // Emitimos hacia la slaida ultima clave del arreglo
		echo array_keys($books)[count($books)-1];
        // echo json_encode($books);
        break;
    
	case 'PUT':
    // validamos que el recurso buscado exista
		if ( !empty($resourceId) && array_key_exists( $resourceId, $books ) ) {
            // tomamos la entreada 'cruda'
			$json = file_get_contents( 'php://input' );
            // tranformamos el json en un nuevo elento del arreglo			
			$books[ $resourceId ] = json_decode( $json, true );
            // retornamos la coleccion modificada en jsom
			echo $resourceId;
		}
		break;   
    
    case 'DELETE':
    // Validando que el recurso exista
		if ( !empty($resourceId) && array_key_exists( $resourceId, $books ) ) {
            // eliminamos el recurso
			unset( $books[ $resourceId ] );
		}
		break;
	default:
	    http_response_code( 404 );

		echo json_encode(
			[
				'error' => $method.' not yet implemented :(',
			]
		);

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
//.........................................
// Necesita la Función Hash, la clave secreta y el UID.
// $ curl http://localhost:8000/books -H 'X-HASH: c9480d6a088c0fb9afc857008dc4da2df1581f78' -H 'X-UID: 1' -H 'X-TIMESTAMP: 1565407453'
// ************************************************
// php -S localhost:8000 router.php
// php -S localhost:8001 auth_server.php
// Pedido al servidor de autenticación:
// $ curl http://localhost:8001 -X 'POST' -H 'X-Client-Id: 1' -H 'X-Secret:SuperSecreto!'
// Pedido al servidor de recursos:
// $ curl http://localhost:8000/books -H 'X-Token: ***'