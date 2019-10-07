# binary-tree

Los pasos para poder ejecutar la solución son los siguientes:

1. Crear la base de datos: Esta debe ser creada en PostgreSQL, en la carpeta 'backup bd' se encuentra el backup de esta.
2. Configurar la base de datos en el proyecto: Una vez creada la base de datos es necesario configurar esta en el proyecto, para esto se debe entrar al siguiente archivo 'app->config->config.php', en este debemos actualizar el usuario y password de postgres, y el nombre que se le dio a la base de datos. Ya con esto el proyec

    'database' => array(
        'schema'   => 'public',
        'adapter'  => 'Postgresql',
        'host'     => '127.0.0.1',
        'username' => 'postgres',
        'password' => '123456',
        'dbname'   => 'binary-tree',
    ),
    
3. En este paso ya el proyecto esta configurado, para ver la solución se deben consumir los siguientes servicios REST:

3.1. 
  CREAR ARBOL BINARIO
  URL: http://localhost//prueba-masivian/binarytree/create
  DATOS:
  {
      "tree_name" : "Prueba arbol",
      "nodes":[{"value":"60","parent":""},
           {"value":"21","parent":"60"},
           {"value":"23","parent":"60"},
           {"value":"31","parent":"21"},
           {"value":"30","parent":"21"},
           {"value":"55","parent":"30"},
           {"value":"15","parent":"23"},
           {"value":"98","parent":"23"},
           {"value":"24","parent":"23"},
           {"value":"11","parent":"10"}
      ]
  }

  OBSERVACION: Estos datos se deben enviar por medio de una peticion POST en formato JSON (raw). 
  tree_name = Nombre del arbol que se desea crear
  nodes = Datos de los nodos que compondran el arbol
  value: Valor del nodo que se creara
  parent: Valor del nodo padre del nodo que se creara

  Si el nodo no tiene nodo padre se debe dejar el campo parent vacio o no se debe agregar este campo

  RESPUESTA: El servicio retornara los nodos que fueron agregados, los nodos que no pudieron ser agregados y los datos del arbol creado.
  {
      "return": true,
      "data": {
          "id_tree": "10",
          "name": "Prueba arbol",
          "add": [
              "60: Nodo agregado",
              "21: Nodo agregado",
              "23: Nodo agregado",
              "31: Nodo agregado",
              "30: Nodo agregado",
              "55: Nodo agregado",
              "15: Nodo agregado",
              "98: Nodo agregado"
          ],
          "fail": [
              "24: Nodo padre con maximo de hijos",
              "11: Nodo padre no encontrado"
          ]
      },
      "message": "Se ha creado el árbol",
      "status": 200
  }

3.2.
  ANCESTRO COMUN MAS CERCANO ENTRE 2 NODOS 
  URL: http://localhost//prueba-masivian/binarytree/lowestCommonAncestor
  DATOS:
  id_tree:9
  node_1:30
  node_2:31

  OBSERVACION: Estos datos se deben enviar por medio de una peticion POST como form-data. 
  id_tree = id del arbol en el que se encuentran los nodos, este es retornado al crearse el arbol.
  node_1 = Nodo 1 del que se desea buscar el ancestro comun
  node_2 = Nodo 2 del que se desea buscar el ancestro comun
  
  RESPUESTA: El servicio retornara con un mensaje cual es el ancestro comun mas cercano de los nodos ingresados
  {
      "return": true,
      "message": "El ancestro común más cercano es 21",
      "status": 200
  }

 
