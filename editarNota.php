<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit1']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}
if (isset($_POST['submit2']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}
$error = false;
$config = include 'config.php';

if (isset($_POST['submit1'])) {
    $resultado = [
        'error' => false,
        'mensaje' => 'La nota ha sido agregada con éxito'
    ];

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $id_alumno = $_GET['id'];
        $referencia = $_POST['referencia'];
        $nota = $_POST['nota'];

        $consultaSQL2 = "INSERT INTO notas
        (id_alumno, referencia, nota) values (".$id_alumno.", '".$referencia."', ".$nota.")";

        $sentencia2 = $conexion->prepare($consultaSQL2);
        $sentencia2->execute();

    } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

if (isset($_POST['submit2'])) {
    $resultado = [
        'error' => false,
        'mensaje' => 'La nota ha sido modificada con éxito'
    ];

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $nota = [
            "id"        => $_GET['edit'],
            "referencia"=> $_POST['nuevareferencia'],
            "nota"      => $_POST['nuevanota']
        ];

        $consultaSQL = "UPDATE notas SET 
            referencia = :referencia,
            nota = :nota
            WHERE id = :id";

        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($nota);

    } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $id = $_GET['id'];
    $nombre = $_GET['nombre'];
    $apellido = $_GET['apellido'];

    $consultaSQL = "SELECT * FROM notas WHERE id_alumno =" . $id;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $notas = $sentencia->fetchAll();

} catch(PDOException $error) {
    $error= $error->getMessage();
}

$titulo = 'Lista de notas del alumno ' . $nombre . ' ' . $apellido ;
?>

<?php include "templates/header.php"; ?>

<?php
if ($resultado) {
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $resultado['mensaje'] ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            </p>
            <h2 class="mt-3"><?= $titulo ?></h2>
            <hr>
            <form method="post">
            <div class="form-group">
                <label for="referencia">Referencia</label>
                <input type="text" name="referencia" id="referencia" class="form-control">
            </div>
            <div class="form-group">
                <label for="Nota">Nota</label>
                <input type="number" step="0.01" name="nota" id="nota" class="form-control">
            </div>
            <div class="form-group">
                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                <input type="submit" name="submit1" class="btn btn-primary" value="Guardar nota">
            </div>
            </form>
            <form method="post">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Referencia</th>
                    <th>Nueva Referencia</th>
                    <th>Nota</th>
                    <th>Nueva Nota</th>
                    <th>Editar nota</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($notas && $sentencia->rowCount() > 0) {
                    foreach ($notas as $fila) {
                        if ($fila["id"] == $_GET['edit']) {
                            ?>
                            <tr method="post">
                                <td><?php echo escapar($fila["id"]); ?></td>
                                <td><?php echo escapar($fila["referencia"]); ?></td>
                                <td class="form-group"><input type="text" name="nuevareferencia" id="nuevareferencia" value="<?= escapar($fila["referencia"]) ?>" class="form-control"></td>
                                <td><?php echo escapar($fila["nota"]); ?></td>
                                <td class="form-group"><input type="number" step="0.01" name="nuevanota" id="nuevanota" value="<?= escapar($fila["nota"]) ?>" class="form-control"></td>
                                <td class="form-group">
                                    <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                                    <input type="submit" name="submit2" class="btn btn-primary" value="Actualizar">
                                    <a href="<?= 'notas.php?id=' . escapar($id) .'&nombre='. escapar($nombre) .'&apellido='. escapar($apellido) ?>"> ✘ Cancelar</a>
                                </td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td><?php echo escapar($fila["id"]); ?></td>
                                <td><?php echo escapar($fila["referencia"]); ?></td>
                                <td></td>
                                <td><?php echo escapar($fila["nota"]); ?></td>
                                <td></td>
                                <td>
                                    <a href="<?= 'editarNota.php?id=' . escapar($id) .'&nombre='. escapar($nombre) .'&apellido='. escapar($apellido) .'&edit=' . escapar($fila["id"]) ?>">✏️Editar</a>
                                    <a href="<?= 'borrarNota.php?idAlumno=' . escapar($id) .'&nombre='. escapar($nombre) .'&apellido='. escapar($apellido) .'&id='. escapar($fila["id"]) ?>">🗑Eliminar</a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                <tbody>
            </table>
            </form>
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
    </div>
</div>

<?php include "templates/footer.php"; ?>
