<?php
    /** Verifica si el usario que intenta acceder a la URL esta logueado */
    require_once 'is_logged.php';

    /** Base de datos */
    require_once '../db.php';
    require_once '../php_conexion.php';

    /** Validar método e informacion recibida por POST */
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['cambio']) && !empty($_POST['cambio']) && isset($_POST['deMoneda']) && !empty($_POST['deMoneda'])){
            $cambio = floatval($_POST['cambio']);
            $deMoneda = $_POST['deMoneda'];
            $current_day = date('d');
            /** Conexion a la base de datos con objeto MySqli */
            
            $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if(!$mysql){
                die("Imposible conectarse a la base de datos: " . $mysql->$connect_error);
            } else {
                $query = $mysql->prepare("UPDATE currencies SET cotizacion = ?, cotizacion_date = ? WHERE id = ?");
                $query->bind_param('dsi', $cambio, $current_day,$moneda_id);
                $cambio = $cambio;
                $current_day = $current_day;
                $moneda_id = $deMoneda;
                $result = $query->execute();
                $query->close();
                $mysql->close();
                if($result){
                    $message = "Cotización agregada correctamente.";
?>
                    <div class="alert alert-success" id="alert" role="alert">
                        <strong>¡Éxito!</strong>
                        <?=$message?>
                    </div>
<?php
                } else {
                    $message = "Algo ha salido mal, intentalo nuevamente.";
?>
                    <div class="alert alert-danger" id="alert" role="alert">
                        <strong>¡Error!</strong>
                        <?=$message?>
                    </div>
<?php
                }
            }

        } else {
            $message = "Debes ingresar los datos correctamente.";
?>
            <div class="alert alert-danger" id="alert" role="alert">
                <strong>¡Error!</strong>
                <?=$message?>
            </div>
<?php
        }
    } else {
        $message = "Algo ha salido mal, intentalo nuevamente.";
?>
        <div class="alert alert-danger" id="alert" role="alert">
            <strong>¡Error!</strong>
            <?=$message?>
        </div>
<?php
    }

?>