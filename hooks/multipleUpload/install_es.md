# Español

## Instalación.

1.- Luego de descomprimir  el contenido del archivo, colocar la carpeta multipleUpload dentro de la carpeta hooks en el directorio de tú proyecto.

2.-  buscar dentro de la carpeta hooks el archivo footer-extras.php y editarlo. colocar el siguiente código php:

    <?php
        //* load library for multipleUpload in Appgini with bootstrap3
        include ('hooks/multipleUpload/scripts.php');
    ?>


3.- refrescar el navegador y verificar que no haya ningún error.  multipleUpload ya está listo para ser utilizado.

## Configuración.

1.- se debe crear, desde el generador de proyectos un campo llamado uploads preferentemente, este campo se debe crear en cada tabla en la que se necesite gestionar un documento.
por ejemplo, crear un campo ulploads en la tabla "productos" y en la tabla "clientes" para gestionar documentos en la tabla productos y en la tabla clientes.

2.- el siguiente codigo deberá estar en cada archivos js de vista de detalle.

    $j(function () { 
        load_images(true);  
    });

    function load_images(addFrame = false) {
        if (!is_add_new()) {
            let data = {
                tn: AppGini.currentTableName(),
                fn: 'uploads', //change this value if use other field name
                id: selected_id(),
            }
            if (addFrame) active_upload_frame(data);
            loadImages(data)
        }
    }

por ejemplo agregar en el archivo productos-dv.js y en el clientes-dv.js, si los archivos no se encuentran hay que crear uno nuevo.
Estos archivos deben estar en la carpeta hooks.

3.- si quiere agregar un boton de acceso desde la vista de tabla para acceder a la libraria deberá agregar el siguiente código en el archivo js de la vista de la tabla:

    $j(function () {
        add_button_TV();
    });

por ejemplo: si quiere un boton en la vista de tabla ,de la tabla productos deberá editar el archivo productos-tv.js y agregar el código. si no encuentra el archivo deberá crearlo dentro de la carpeta hooks.

4.- si no graba los archivos verificar los permisos de escritura dentro de la carpeta images


## Resumiendo.

    1.- descomprimir archivo adjunto
    2.- colocar la carpeta multipleUpload dentro de la carpeta hooks
    3.- editar y agregar el código en el archivo footer-extras.php
    4.- crear el campo uploads dentro de la tabla que necesite gestionar documentos.
    5.- editar y colocar el código en tablename-dv.js
    6.- editar y colocar el código en tablename.tv.js si necesita.
    7.- controlar los permisos de escritura dentro de la carpeta images.

