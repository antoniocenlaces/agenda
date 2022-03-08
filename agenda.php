<?php


// RF1  Si he apretado submit

// RF2 Leer valores del formulario (nombre, tel, agenda)
$nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
//...

//RF3 Vamos a establecer una variable de error
$error = null;
/*Identica los posibles errores a considerar:
   1.- El nombre está vacío
   2.- El teléfono no es numérico
   3.-
*/

//Creamos las funciones necesarias para
//Obtener el error
$error = valida_nombre($nombre);
//...

/*
RF 4, el kernel del ejercicio:
 Ahora ya tenemos los datos del usuario RF1 y posible error RF 2
 Actuamos en consecuencia:
//Si hay error, informamos de ello
//Si no  hay error realizamos la acción selecciona (add o borrar)
*/
if ($error) {

} else {
    //Realizamos la acción seleccionada (borrar, actualizar )
    //Generamos un mensaje , ya que la acción añadir puede ser una modificación del teléfono
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="index.php" method="POST">
    <?php
    foreach ($agenda as $nobmre => $tel) {
        echo "<input type='hidden' name='agenda[$nombre]' value ='$tel'>\n";
    } ?>
</form>

</body>
</html>
