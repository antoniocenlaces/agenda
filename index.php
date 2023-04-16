<?php
// Comentarios para probar git
// Segundo comentario para probar GIT
// RF1  Si he apretado submit
$disabled="disabled";
function valida_nombre($nombre) {
    $retorno = false;
    $expresion ="#^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð\ \'-]*$#";
    $ok = preg_match($expresion, $nombre);
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
        $tel = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        if ($tel == "") {
            //comprobamos si ese nombre ya existe en la agenda
            if (isset($agenda[$nombre])) { //si que existe y se puede borrar
                return false;
            } else {
                return "No se puede borrar un contacto inexistente";
            }
        } //$tel contiene algo.analicemos.
        $expresion ="#^[+0-9][0-9]*$#";
        $ok = preg_match($expresion, $tel); //comparo para ver si $tel contiene sólo caracteres numéricos
        if ($ok) { // Teléfono contiene sólo números
            return false;
        } else { // el campo teléfono  no es válido
            return "El campo teléfono puede iniciar con el caracter \'+\' y solo puede contener números";
        }
    }

if (isset($_POST['f1'])) {
// RF2 Leer valores del formulario (nombre, tel, agenda)
    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $agenda = $_POST['agenda'] ?? []; // cargo el array $agenda del formulario oculto
    var_dump($agenda);
    $disabled = "";
    if (count($agenda)==0) //si $agenda está vacía, desabilito el botón de borrar
        $disabled="disabled";
//RF3 Vamos a establecer una variable de error
    $error = false;
    if ($_POST['f1']<>'Borrar') { //caso de pulsar el botón Borrar no evalua el contenido de los campos
        $error = valida_nombre($nombre);   // valido si el nombre es válido contra una expresión regular o si está vacío
        if (!$error)
            $error = valida_telefono($tel, $nombre, $agenda); //valido si el teléfono es numérico. si estuviese vacío es para borrar el nombre si existe.
    }
    if (!$error) {
        //Realizamos la acción seleccionada (borrar, actualizar )
        //Generamos un mensaje , ya que la acción añadir puede ser una modificación del teléfono
        $opcion = $_POST['f1'];
        switch ($opcion) {
            case "Borrar":
                $contactos = sizeof($agenda);
                $agenda = [];
                $msj = "Se han borrado $contactos contactos de la agenda";
                $disabled="disabled";
                break;
            case "Añadir":
                //1
                  if ($tel == "") {
                     unset ($agenda[$nombre]); //Elimino un contacto
                    $msj = "Se ha elmininado el contacto de <span style='color:green'>$nombre</span>";
                } else {
                    if (isset($agenda[$nombre])) {
                        $msj = "Se ha modificado el contacto de <span style='color:green'>$nombre</span>";
                    } else {
                        $msj = "Se ha añadido el contacto de <span style='color:green'>$nombre</span>";
                    }
                      $agenda[$nombre] = $tel;
                    $disabled="";
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
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <title>Agenda</title>
    <?php
    if ($error)
        echo "<script>alert('$error');</script>";
    ?>
</head>
<body>
    <div class="container my-auto">
        <h1>Agenda Interactiva</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-7">
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
                        <button type="submit" name="f1" value="Añadir" class="btn envia">Enviar</button>
                        <button type="submit" name="f1" value="Borrar" class="btn borra" <?=$disabled?>>Borrar</button>
                    </div>
                    <?php
                    foreach ($agenda as $nombre => $tel) {
                        echo "<input type='hidden' name='agenda[$nombre]' value ='$tel'>\n";
                    } ?>
                </form>
            </div>
            <div class="col-5">
                <h4>Contenido de la Agenda</h4>
                <table>
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
            </div>
        </div>
    </div>
    <hr>
    <?php
    // Imprimo el $msj con la acción realizada
    if (isset($msj))
        echo "<h4>$msj</h4>";
    ?>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
