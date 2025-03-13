RegistrarProspectosBundle
=========================

Libreria común para registrar desde algun formulario los datos capturado al TecnoCRM o al TecnoMercadeo a través de sus respectivas API. Ideal para los proyectos de estrategias digitales u otro uso de captura de datos.


Requisitos
--------------------
- [Composer](https://getcomposer.org/)
- Recomendado el [Plugin Rest Api](https://resources.phplist.com/plugin/restapi)  de TecnoMercadeo


Instalación
--------------------

1. Agregar el repositorio privado en el archivo `composer.json` del proyecto

	``` json
	{
	    "require": {
	        "tsv/registrar_prospectos": "dev-master"
	    },
	    "repositories": [
	        {
	            "type": "vcs",
	            "url":  "https://respaldo:8443/r/Especiales/RegistrarProspectosBundle.git"
	        }
	    ]
	}
	```

2. Instalar via Composer: `composer install tsv/registrar_prospectos`
3. Agregar el autoloader del al archivo principal de la plataforma o proyecto, por ejemplo para el CMS es el mainfile.php, para el commercio el aplication_top.php, etc.

Nota: en caso no tener el archivo de composer crearlo en la raiz del proyecto y ejecutar el paso 1.


Uso
--------------------

Se pueden registrar en ambas plataformas o por separado, dependiendo de las opciones pasadas al constructor se habilita su envio a dichas plataformas.


1. Ubicar el sitio donde se creara el Objeto e Iniciar la clase para registrar en el TecnoCRM o TecnoMercadeo.

	``` php
	// EnTuArchivo.php
	use TSV\Component\RegistrarProspectos\RegistrarProspectos;
	//...
	$registroProspecto = new RegistrarProspectos(array(
	    'TecnoCRM' => array(
	        'webservice_url' => 'https://nombredeldominio.ext/tcrm/webservice.php',
	        'username' => 'usuario_del_crm',
	        'access_key' => 'api_key_del_crm', //se obtiene en las propiedades del usuario
	    ),
	    'TecnoMercadeo' => array(
	        'tmerc_url' => 'https://nombredeldominio.ext/tmerc/',
	        'username' => 'usuario_del_mercadeo',
	        'access_key' => 'clave_de_acceso',
	    ),
	));
	```

2. Asignar los valores a enviar al TecnoCRM (el dato `cf_765` depende del campo personalizado del CRM)

	``` php
		$registroProspecto->registrarProspectoCRM(array(
	        'assigned_user_id' => '20x2',//ModuloIdxUserId
	        'lastname' => $nombre,
	        'email' => $contactEmail,
	        'phone' => $telefono,
	        'cf_765' => utf8_encode('Formulario Llamado a la Acción'),
	    ));
	```

3. Asignar los valores a enviar al TecnoMercadeo

	```php
		$registroProspecto->registrarProspectoTmercApi(array(
	        'listId' => 1,
	        'email' => $contactEmail,
	    ));
	```



Notas adicionales
--------------------

Las url son obligatorias para la construcción del objeto sin embargo los datos de usuario y clave pueden tambien pueden ser asignados a través del metodo setAuthData en cada plataforma.

	``` php
	    $RegistrarProspectos->tcrm->setAuthData($usuario,$clave); 
	```

Mas documentación al uso puede ser agregada proximamente.
