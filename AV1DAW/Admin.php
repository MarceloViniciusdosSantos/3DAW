<?php
    include "User.php";
    include "Perguntas.php";

    $Usuarios = carregarUser();
    $Perguntas = carregarPerguntas();
    $mensagem = '';

    if(isset($_POST['acton'])){
        $action = $_POST['action'];

        switch ($action) {
            case 'criar_perguntaME':
                
    }
}
?>
