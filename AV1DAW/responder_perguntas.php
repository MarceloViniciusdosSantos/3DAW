<?php
include "Perguntas.php";
include "User.php";

$perguntas = carregarPerguntas();
$usuarios = carregarUser();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $respostas = $_POST['respostas'];

    $mensagem = "Respostas salvas com sucesso para o usuário ID: " . htmlspecialchars($usuario_id);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Responder Perguntas</title>
    <style>
        .container-table {
            width: 800px;
            margin: 0 auto;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .content-table {
            width: 100%;
            background: white;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .form-table {
            width: 100%;
        }
        .nav-table {
            width: 100%;
            padding: 10px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .pergunta {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 3px solid #28a745;
        }
        button {
            background: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .link {
            color: #007bff;
            text-decoration: none;
            margin-right: 15px;
        }
        h1, h2 {
            color: #333;
        }
        .mensagem {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
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

                            <?php if($mensagem): ?>
                                <div class="mensagem"><?php echo $mensagem; ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>Selecione o Usuário:</label>
                                            <select name="usuario_id" required>
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
                                                <button type="submit">Enviar Respostas</button>
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