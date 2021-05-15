<?php
    $varLogout = $_GET['logout'];

    // inicia a sessão
    session_start();
        
    // muda o valor de logged_in para false
    $_SESSION['logged_in'] = false;

    // retorna para a index.php
    header('Location: ../index.php');

    //Apenas para nao mostrar alerta quando o usuario clica em home.
    if($varLogout == 'on'){
        $_SESSION['alerts'] = 'logout';
    }
    

?>