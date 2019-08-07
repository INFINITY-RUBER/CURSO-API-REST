#¿Qué significa REST? y ¿qué es una API RESTful?
REST es un acrónimo de Representational State Transfer o transferencia de estado representacional, le agrega una capa muy delgada de complejidad y abstracción a HTTP. Mientras que HTTP es transferencia de archivos, REST se basa en la transferencia de recursos.

Una API RESTful es una API diseñada con los conceptos de REST:

-Recurso: todo dentro de una API RESTful debe ser un recurso.
-URI: los recursos en REST siempre se manipulan a partir de la URI, identificadores universales de recursos.
-Acción: todas las peticiones a tu API RESTful deben estar asociadas a uno de los verbos de HTTP: GET para obtener un recurso, POST para escribir un recurso, PUT para modificar un recurso y DELETE para borrarlo.
REST es muy útil cuando:
Las interacciones son simples.
Los recursos de tu hardware son limitados.
No conviene cuando las interacciones son muy complejas.
Respuesta a:
¿Qué significa REST? y ¿qué es una API RESTful?
HTTP se basa en el intercambio de archivos y
REST se basa en el intercambio de recursos
archivos: imagenes, archivos .*
recursos: son datos, con los cuales se puede interactuar o consumir haciendo operaciones CRUD en la BD por medio de los métodos HTTP (GET, POST, PUT, DELETE). Todos los recursos tienen un identificador único llamado URI
# Cómo realizar una petición REST e interpretar sus resultados
Utilizando el comando ‘curl’ dentro de nuestra terminal podemos realizar peticiones a cualquier sitio web, por ejemplo una API como la de xkcd.
`curl https://xkcd.com/info.0.json
`
```javascript
```
```javascript
```
```javascript
```
```javascript
```
```javascript
```