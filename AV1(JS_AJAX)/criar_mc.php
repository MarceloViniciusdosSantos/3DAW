<?php
include "Perguntas.php";

$perguntas = carregarPerguntas();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $respostas = array_map('trim', explode(',', $_POST['respostas']));
    $mensagem = criarPerguntaME($perguntas, $_POST['pergunta'], $respostas, $_POST['correta']);
    
    // Para requisições AJAX, apenas retorna a mensagem
    echo $mensagem;
    exit;
}

// Se não for POST, exibe o HTML normal
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta MC</title>
    <script>
        function EnviarPerguntaMC() {
            let pergunta = document.getElementById("pergunta").value;
            let respostas = document.getElementById("respostas").value;
            let correta = document.getElementById("correta").value;
            
            let msg = ValidaForm(pergunta, respostas, correta);
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
                    window.location.href = "listar.php?mensagem=" + encodeURIComponent(this.responseText);
                } else if (this.readyState < 4) {
                    console.log("3: " + this.readyState);
                } else {
                    console.log("Requisicao falhou: " + this.status);
                }
            }
            console.log("4");
            xmlhttp.open("POST", "criar_mc.php");
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("pergunta=" + encodeURIComponent(pergunta) + 
                        "&respostas=" + encodeURIComponent(respostas) + 
                        "&correta=" + encodeURIComponent(correta));
            console.log("enviei form");
            console.log("5");
        }

        function ValidaForm(pergunta, respostas, correta) {
            let msg = "";
            if (pergunta == "") msg = "Pergunta não preenchida. <br>";
            if (respostas == "") msg += "Respostas não preenchidas. <br>";
            if (correta == "") msg += "Resposta correta não preenchida. <br>";
            return msg;
        }
    </script>
    <style>
        /* Estilos mantidos iguais */
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
                            <h2>Criar Pergunta Múltipla Escolha</h2>

                            <form action="" method="POST" name="formPergunta" id="formPergunta">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>Pergunta:</label>
                                            <input type="text" name="pergunta" id="pergunta" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Respostas (separadas por vírgula):</label>
                                            <input type="text" name="respostas" id="respostas" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Resposta Correta:</label>
                                            <input type="text" name="correta" id="correta" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="button" value="Salvar Pergunta" onclick="EnviarPerguntaMC();">
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
                                        <a href="listar.php" class="link">Ir para Lista de Perguntas</a>
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