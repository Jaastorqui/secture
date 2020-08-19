# Prueba Tecnica Secture

_Debemos desarrollar una API REST que permita gestionar los jugadores y equipos a losque pertenecen_

## Requisitos

* Docker
* Añadir en el hosts **secture.local**

## Pasos para instalar 🚀

_Para la ejecución de codigo es necesario seguir unos pasos._
_Una vez tengamos instalado docker, ejecutar estos comandos:_

* docker-composer up
* php bin/console doctrine:migrations:migrate (en la carpeta raiz del proyecto)

## Como probar la API

_En la carpeta [resources](./resources) he dejado dos http para poder probar las llamadas con PHPSTORM_

### Como aplicar filtros 🔧en las peticiones

_Las peticiones GET de player se pueden añadir filtros segun se quiera_

_Los parametros son:_

```
    currency => codigo ISO de la moneda a filtrar_
    position => Posicion del jugador a filtrar
    team => Equipo del filtro
```

_Se puede filtrar por uno, dos, o todos a la vez, por ejemplo,  ?currency=USD&position=forward&team=athletic_


## Construido con 🛠️🛠️

_Menciona las herramientas que utilizaste para crear tu proyecto_

* Symfony 5  
* Docker 
* Nginx 
* Mysql 8

_De dejado en la carpeta  [docker/mysql/migrations](./docker/mysql/migrations) el SQL dump usado de la prueba con la estructura_ 
