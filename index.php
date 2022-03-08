<?php
// RF1  Si he apretado submit
$disabled="disabled";
function valida_nombre(&$nombre)
{
    $retorno = false;
    $nombre = trim($nombre);
    if ($nombre == "")
        $retorno = "El nombre no puede estar vacío";

    return $retorno;

}
function valida_telefono($tel,$nombre,$agenda) {
    return false;
}

if (isset($_POST['f1'])) {
// RF2 Leer valores del formulario (nombre, tel, agenda)
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $tel = filter_input(INPUT_POST, 'telefono', FILTER_VALIDATE_INT);
    $agenda = $_POST['agenda'] ?? [];
    $disabled = "";
    echo "Nombre después de filtrar: $nombre <br>";
    echo "Teléfono después de filtrar: $tel  <br>";
    echo "contenido de \$agenda:  <br>";
    var_dump($agenda);
    if (count($agenda)==0)
        $disabled="disabled";
//RF3 Vamos a establecer una variable de error
    $error = false;
    $error = valida_nombre($nombre);
    if (!$error)
        $error=valida_telefono($tel,$nombre,$agenda);

    /*Identica los posibles errores a considerar:
       1.- El nombre está vacío
       2.- El teléfono no es numérico
       3.-
    */

//Creamos las funciones necesarias para
//Obtener el error
    //$error = valida_nombre($nombre);
//...

    /*
    RF 4, el kernel del ejercicio:
     Ahora ya tenemos los datos del usuario RF1 y posible error RF 2
     Actuamos en consecuencia:
    //Si hay error, informamos de ello
    //Si no  hay error realizamos la acción selecciona (add o borrar)
    */
    if ($error) {
    echo $error;
    } else {
        //Realizamos la acción seleccionada (borrar, actualizar )
        //Generamos un mensaje , ya que la acción añadir puede ser una modificación del teléfono
        echo "<p>No hay errores voy a analizar las acciones</p>";
        $opcion = $_POST['f1'];
        echo "<p>Valor de opción leido del botón: $opcion</p>";
        switch ($opcion) {
            case "Borrar contactos":
                echo "<p>Han pedido borrar todos los contactos</p>";
                $agenda = [];
                $contactos = sizeof($agenda);
                $msj = "Se han borrado $contactos contactos de la agenda";
                break;
            case "Añadir":
                //1
                echo "<p>Han pedido Añadir. Vamos a ver el qué.</p>";
                if ($tel == "") {
                    echo "<p>Como el teléfono está vacío voy a borrar $agenda[$nombre]</p>";
                    unset ($agenda[$nombre]); //Elimino un contacto
                    $msj = "Se ha elmininado el contacto de <span style='color:green'>$nombre</span>";
                } else {
                    if (isset($agenda[$nombre])) {
                        echo "<p>El nombre escrito existe y se va a modificar</p>";
                        $agenda[$nombre] = $tel;
                        $msj = "Se ha modificado el contacto de <span style='color:green'>$nombre</span>";
                    } else {
                        echo "<p>Hay un nombre nuevo y voy a añadir</p>";
                        $msj = "Se ha añadido el contacto de <span style='color:green'>$nombre</span>";
                        $agenda[$nombre] = $tel; //Add un contacto
                    }
                }
                break;
        }
    }

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<form action="index.php" method="POST">
    <fieldset>
        <legend>Introducción de datos para Agenda</legend>
        <div class="form-block">
            <label class="form-label" for="nombre" >Nombre</label>
            <input class="form-control" type="text" name="nombre" id="nombre">
        </div>
        <div class="form-block">
            <label class="form-label" for="telefono" >Teléfono</label>
            <input class="form-control" type="text" name="telefono" id="telefono">
        </div>
    </fieldset>
    <div class="form-block">
        <button type="submit" name="f1" value="Añadir">Enviar</button>
        <button type="reset" name="f1" value="Borrar contactos" class="reset" <?=$disabled?>>Borrar</button>
    </div>
    <?php
    foreach ($agenda as $nombre => $tel) {
        echo "<input type='hidden' name='agenda[$nombre]' value ='$tel'>\n";
    } ?>
</form>
<table>
    <caption>Contenido de la Agenda</caption>
    <tbody>
        <thead>
            <tr><th>Nombre</th><th>Teléfono</th></tr>
        </thead>
        <?php
        foreach ($agenda as $nombre => $tel) {
            echo "<tr><td>$nombre</td><td>$tel</td></tr>";
        } ?>
    </tbody>
</table>
</body>
</html>
