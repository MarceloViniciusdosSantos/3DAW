<?php
include "User.php";

$usuarios = carregarUser();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id']);
    $nome = trim($_POST['nome']);
    
    if ($id === '' || $nome === '') {
        $mensagem = 'Preencha todos os campos.';
    } else {
        $mensagem = criarUser($usuarios, $id, $nome);
    }
    
 
    echo $mensagem;
    exit;
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <script>
        function EnviarUsuario() {
            let id = document.getElementById("id").value;
            let nome = document.getElementById("nome").value;
            
            let msg = ValidaForm(id, nome);
            if (msg != "") {
                document.getElementById("msg").innerHTML = msg;
                return;
            }

            let xmlhttp = new XMLHttpRequest();
            console.log("1");
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("Chegou a resposta OK: " + this.responseText);
                    console.log("2");
                    window.location.href = "listar_user.php?mensagem=" + encodeURIComponent(this.responseText);
                } else if (this.readyState < 4) {
                    console.log("3: " + this.readyState);
                } else {
                    console.log("Requisicao falhou: " + this.status);
                }
            }
            console.log("4");
            xmlhttp.open("POST", "criar_user.php");
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + encodeURIComponent(id) + "&nome=" + encodeURIComponent(nome));
            console.log("enviei form");
            console.log("5");
        }

        function ValidaForm(id, nome) {
            let msg = "";
            if (id == "") msg = "ID não preenchido. <br>";
            if (nome == "") msg += "Nome não preenchido. <br>";
            return msg;
        }
    </script>
    <style>
    
        .container-table { width: 800px; margin: 0 auto; background-color: #f5f5f5; padding: 20px; }
        .content-table { width: 100%; background: white; padding: 25px; border-radius: 8px; border-left: 4px solid #007bff; }
        .form-table { width: 100%; }
        .nav-table { width: 100%; padding: 10px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input[type="text"] { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 4px; font-size: 16px; margin-bottom: 20px; }
        input[type="text"]:focus { outline: none; border-color: #007bff; }
        input[type="button"] { background: #007bff; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .link { color: #007bff; text-decoration: none; margin-right: 15px; }
        #msg { color: #d9534f; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <table class="container-table">
        <tr>
            <td>
                <table class="content-table">
                    <tr>
                        <td>
                            <h2>Cadastrar Usuário</h2>

                            <form action="" method="POST" name="formUsuario" id="formUsuario">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>ID:</label>
                                            <input type="text" name="id" id="id" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Nome:</label>
                                            <input type="text" name="nome" id="nome" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="button" value="Cadastrar" onclick="EnviarUsuario();">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p id="msg"></p>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                            <table class="nav-table">
                                <tr>
                                    <td>
                                        <a href="Admin.php" class="link">Voltar ao Menu</a>
                                        <a href="listar_user.php" class="link">Ver Usuários</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>