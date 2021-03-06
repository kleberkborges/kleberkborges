<?php
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once "../libraries/password_compatibility_library.php";
}
if (empty($_POST['user_id_mod'])) {
    $errors[] = "ID vacío";
} elseif (empty($_POST['user_password_new3']) || empty($_POST['user_password_repeat3'])) {
    $errors[] = "Contraseña vacía";
} elseif ($_POST['user_password_new3'] !== $_POST['user_password_repeat3']) {
    $errors[] = "la contraseña y la repetición de la contraseña no son lo mismo";
} elseif (
    !empty($_POST['user_id_mod'])
    && !empty($_POST['user_password_new3'])
    && !empty($_POST['user_password_repeat3'])
    && ($_POST['user_password_new3'] === $_POST['user_password_repeat3'])
) {
    require_once "../db.php";
    require_once "../php_conexion.php";

    $user_id       = intval($_POST['user_id_mod']);
    $user_password = $_POST['user_password_new3'];

    // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
    // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
    // PHP 5.3/5.4, by the password hashing compatibility library
    $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

    // write new user's data into database
    $sql   = "UPDATE users SET con_users='" . $user_password_hash . "' WHERE id_users='" . $user_id . "'";
    $query = mysqli_query($conexion, $sql);

    // if user has been added successfully
    if ($query) {
        $messages[] = "Contraseña actualizada correctamente.";
    } else {
        $errors[] = "Algo ha salido mal, intentalo nuevamente.";
    }

} else {
    $errors[] = "Un error desconocido ocurrió.";
}

if (isset($errors)) {

    ?>
    <div class="alert alert-danger" role="alert">
        <strong>¡Error! </strong>
        <?php
foreach ($errors as $error) {
        echo $error;
    }
    ?>
    </div>
    <?php
}
if (isset($messages)) {

    ?>
    <div class="alert alert-success" role="alert">
        <strong>¡Éxito!</strong>
        <?php
foreach ($messages as $message) {
        echo $message;
    }
    ?>
    </div>
    <?php
}

?>