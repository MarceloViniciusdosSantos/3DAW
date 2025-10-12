<?php
include "Perguntas.php";
include "User.php";

$perguntas = carregarPerguntas();
$usuarios = carregarUser();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $respostas = json_decode($_POST['respostas'], true);

    $mensagem = "Respostas salvas com sucesso para o usuário ID: " . htmlspecialchars($usuario_id);
    

    echo $mensagem;
    exit;
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Responder Perguntas</title>
    <script>
        function EnviarRespostas() {
            let usuario_id = document.getElementById("usuario_id").value;
            
            let msg = ValidaForm(usuario_id);
            if (msg != "") {
                document.getElementById("msg").innerHTML = msg;
                return;
            }

      
            let respostas = {};
            let inputs = document.querySelectorAll('input[name^="respostas"]');
            inputs.forEach(function(input) {
                if (input.type === 'radio') {
                    if (input.checked) {
                        let name = input.name.match(/\[(\d+)\]/)[1];
                        respostas[name] = input.value;
                    }
                } else {
                    let name = input.name.match(/\[(\d+)\]/)[1];
                    respostas[name] = input.value;
                }
            });

            let xmlhttp = new XMLHttpRequest();
            console.log("1");
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("Chegou a resposta OK: " + this.responseText);
                    console.log("2");
                    document.getElementById("msg").innerHTML = this.responseText;
                    document.getElementById("msg").style.color = "#155724";
                    document.getElementById("msg").style.backgroundColor = "#d4edda";
                    document.getElementById("msg").style.padding = "10px";
                    document.getElementById("msg").style.borderRadius = "4px";
                } else if (this.readyState < 4) {
                    console.log("3: " + this.readyState);
                } else {
                    console.log("Requisicao falhou: " + this.status);
                }
            }
            console.log("4");
            xmlhttp.open("POST", "responder_perguntas.php");
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("usuario_id=" + encodeURIComponent(usuario_id) + 
                        "&respostas=" + encodeURIComponent(JSON.stringify(respostas)));
            console.log("enviei form");
            console.log("5");
        }

        function ValidaForm(usuario_id) {
            let msg = "";
            if (usuario_id == "") msg = "Selecione um usuário. <br>";
            
    
            let todasRespondidas = true;
            let inputs = document.querySelectorAll('input[name^="respostas"]');
            let perguntasRespondidas = new Set();
            
            inputs.forEach(function(input) {
                if (input.type === 'radio' && input.checked) {
                    let name = input.name.match(/\[(\d+)\]/)[1];
                    perguntasRespondidas.add(name);
                } else if (input.type === 'text' && input.value.trim() !== '') {
                    let name = input.name.match(/\[(\d+)\]/)[1];
                    perguntasRespondidas.add(name);
                }
            });

            <?php
            $totalPerguntas = count($perguntas);
            echo "let totalPerguntas = $totalPerguntas;";
            ?>

            if (perguntasRespondidas.size < totalPerguntas) {
                msg += "Responda todas as perguntas antes de enviar. <br>";
            }

            return msg;
        }
    </script>
    <style>

        .container-table { width: 800px; margin: 0 auto; background-color: #f5f5f5; padding: 20px; }
        .content-table { width: 100%; background: white; padding: 25px; border-radius: 8px; border-left: 4px solid #007bff; }
        .form-table { width: 100%; }
        .nav-table { width: 100%; padding: 10px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input[type="text"], select { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 4px; font-size: 16px; margin-bottom: 20px; }
        .pergunta { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 3px solid #28a745; }
        input[type="button"] { background: #007bff; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .link { color: #007bff; text-decoration: none; margin-right: 15px; }
        #msg { margin: 10px 0; font-weight: bold; }
    </style>
</head>
<body>
    <table class="container-table">
        <tr>
            <td>
                <table class="content-table">
                    <tr>
                        <td>
                            <h2>Responder Perguntas</h2>

                            <div id="msg"></div>

                            <form action="" method="POST" name="formRespostas" id="formRespostas">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>Selecione o Usuário:</label>
                                            <select name="usuario_id" id="usuario_id" required>
                                                <option value="">Selecione um usuário</option>
                                                <?php foreach ($usuarios as $id => $usuario): ?>
                                                    <option value="<?php echo $id; ?>">
                                                        <?php echo htmlspecialchars($usuario['nome'] . " (ID: " . $id . ")"); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <?php if ($perguntas): ?>
                                        <?php foreach ($perguntas as $i => $p): ?>
                                            <tr>
                                                <td>
                                                    <div class="pergunta">
                                                        <strong>Pergunta <?php echo ($i + 1); ?>:</strong><br>
                                                        <?php echo htmlspecialchars($p['pergunta']); ?>
                                                        
                                                        <?php if ($p['tipo'] == 'mE'): ?>
                                                            <br><br>
                                                            <?php foreach ($p['respostas'] as $resposta): ?>
                                                                <label>
                                                                    <input type="radio" name="respostas[<?php echo $i; ?>]" value="<?php echo htmlspecialchars($resposta); ?>" required>
                                                                    <?php echo htmlspecialchars($resposta); ?>
                                                                </label><br>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <br><br>
                                                            <input type="text" name="respostas[<?php echo $i; ?>]" placeholder="Digite sua resposta..." required style="width: 100%;">
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td>
                                                <p>Nenhuma pergunta cadastrada.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    
                                    <?php if ($perguntas): ?>
                                        <tr>
                                            <td>
                                                <input type="button" value="Enviar Respostas" onclick="EnviarRespostas();">
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </form>

                            <table class="nav-table">
                                <tr>
                                    <td>
                                        <a href="Admin.php" class="link">Voltar ao Menu</a>
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