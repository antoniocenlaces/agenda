<?php
// RF1  Si he apretado submit
$disabled="disabled";
function valida_nombre(&$nombre) {
    $retorno = false;
    $expresion ="#^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð\ \'-]*$#";
    $ok = preg_match($expresion, $nombre);
    echo "<p>Validando nombre $nombre. preg_match ha devuelto $ok</p>";
    if ($nombre == "") {
        $retorno = "El nombre no puede estar vacío.";
    }
    elseif (!$ok) {
        $retorno = "El nombre solo puede contener caracteres válidos y no numéricos.";
    }
    return $retorno;
}
function valida_telefono(&$tel,$nombre,$agenda) {
    //cargamos el valor del campo teléfono con la función filter_input.
    //si se ha introducido un valor no válido la función nos devuelve false
    if ($_POST['f1']<>"Borrar") {
        $tel = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        if (filter_var($tel,FILTER_VALIDATE_INT)===0 || filter_var($tel,FILTER_VALIDATE_INT)) { // Teléfono contiene sólo números
            return false;
        } else { // el campo teléfono está vacío o no es válido
            // Puede que esté vacío pq queremos borrar ese registro
            if ($_POST['telefono'] == "") {
                //comprobamos si ese nombre ya existe en la agenda
                if (isset($agenda[$nombre])) { //si que existe y se puede borrar
                    return false;
                } else {
                    return "No se puede borrar un contacto inexistente";
                }
            }
            return "El campo teléfono solo puede contener números";
        }
    } else {
        return false;
    }
}
if (isset($_POST['f1'])) {
// RF2 Leer valores del formulario (nombre, tel, agenda)
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $tel = $_POST['telefono'];
    $filtrada=filter_var($tel,FILTER_VALIDATE_INT);
    echo "<p>Resultado de filter_var al iniciar código: $filtrada</p>";
    var_dump($filtrada);
    $agenda = $_POST['agenda'] ?? []; // cargo el array $agenda del formulario oculto
    $disabled = "";
    echo "Nombre después de filtrar: $nombre <br>";
    echo "Teléfono después de filtrar: $tel  <br>";
    var_dump($tel);
    echo "contenido de \$agenda:  <br>";
    var_dump($agenda);
    if (count($agenda)==0) //si $agenda está vacía, desabilito el botón de borrar
        $disabled="disabled";
//RF3 Vamos a establecer una variable de error
    $error = false;
    // valido si el nombre es válido contra una expresión regular o si está vacío
    $error = valida_nombre($nombre);
    //valido si el teléfono es numérico. si estuviese vacío es para borrar el nombre si esxiste. si se ha pulsado Borrar deja pasar
    if (!$error || ($_POST['f1']=='Borrar'))
        $error=valida_telefono($tel,$nombre,$agenda);
    if (!$error) {
        //Realizamos la acción seleccionada (borrar, actualizar )
        //Generamos un mensaje , ya que la acción añadir puede ser una modificación del teléfono
        echo "<p>No hay errores voy a analizar las acciones</p>";
        $opcion = $_POST['f1'];
        echo "<p>Valor de opción leido del botón: $opcion</p>";
        switch ($opcion) {
            case "Borrar":
                echo "<p>Han pedido borrar todos los contactos</p>";
                $contactos = sizeof($agenda);
                $agenda = [];
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
                    $disabled="";
                }
                break;
        }
        echo "<p>Al acabar de analizar las acciones contenido de \$agenda:</p>";
        var_dump($agenda);
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
    <?php
        if ($error)
        echo "<script>alert('$error');</script>";
    ?>
    <title>Agenda</title>
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
        <button type="submit" name="f1" value="Añadir" class="envia">Enviar</button>
        <button type="submit" name="f1" value="Borrar" class="borra" <?=$disabled?>>Borrar</button>
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
<hr>
<?php
// Imprimo el $msj con la acción realizada
if (isset($msj))
    echo "<h4>$msj</h4>";
?>
</body>
</html>
