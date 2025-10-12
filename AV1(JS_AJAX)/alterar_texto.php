<?php
include "Perguntas.php";

$perguntas = carregarPerguntas();
$index = isset($_GET['index']) ? intval($_GET['index']) : -1;
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mensagem = alterarPerguntaTexto($perguntas, $_POST['index'], $_POST['pergunta']);
    

    echo $mensagem;
    exit;
}

$pergunta = ($index >= 0 && isset($perguntas[$index])) ? $perguntas[$index] : null;


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alterar Pergunta Texto</title>
    <script>
        function EnviarAlteracao() {
            let pergunta = document.getElementById("pergunta").value;
            let index = document.getElementById("index").value;
            
            let msg = ValidaForm(pergunta);
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
            xmlhttp.open("POST", "alterar_texto.php");
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("index=" + encodeURIComponent(index) + "&pergunta=" + encodeURIComponent(pergunta));
            console.log("enviei form");
            console.log("5");
        }

        function ValidaForm(pergunta) {
            let msg = "";
            if (pergunta == "") msg = "Pergunta não preenchida. <br>";
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
                            <h2>Alterar Pergunta Texto</h2>
                            
                            <?php if ($pergunta && $pergunta['tipo'] == 'texto'): ?>
                            <form action="" method="POST" name="formAlterar" id="formAlterar">
                                <input type="hidden" name="index" id="index" value="<?php echo $index; ?>">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>Pergunta:</label>
                                            <input type="text" name="pergunta" id="pergunta" value="<?php echo htmlspecialchars($pergunta['pergunta']); ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="button" value="Salvar Alterações" onclick="EnviarAlteracao();">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p id="msg"></p>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <?php else: ?>
                                <p>Pergunta não encontrada ou não é do tipo texto.</p>
                            <?php endif; ?>

                            <table class="nav-table">
                                <tr>
                                    <td>
                                        <a href="listar.php" class="link">Voltar à Lista</a>
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