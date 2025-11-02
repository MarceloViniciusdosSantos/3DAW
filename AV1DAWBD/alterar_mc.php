<?php
session_start();
include "Perguntas.php";

$perguntas = carregarPerguntas();
$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
$mensagem = "";

// Encontrar a pergunta pelo ID
$pergunta = null;
foreach ($perguntas as $p) {
    if ($p['id'] == $id && $p['tipo'] == 'mE') {
        $pergunta = $p;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $pergunta_texto = trim($_POST['pergunta']);
    $respostas = array_map('trim', explode(',', $_POST['respostas']));
    $correta = trim($_POST['correta']);
    
    if (empty($pergunta_texto) || empty($respostas) || empty($correta)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios!";
        $_SESSION['tipo_mensagem'] = 'erro';
    } else {
        $resultado = alterarPerguntaMC($id, $pergunta_texto, $respostas, $correta);
        $_SESSION['mensagem'] = $resultado;
        $_SESSION['tipo_mensagem'] = 'sucesso';
    }
    
    header("Location: listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alterar Pergunta MC</title>
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
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
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
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 4px;
            margin-bottom: 20px;
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
                            <h2>Alterar Pergunta Múltipla Escolha</h2>
                            
                            <?php if (isset($_SESSION['mensagem'])): ?>
                                <div class="mensagem"><?php echo htmlspecialchars($_SESSION['mensagem']); ?></div>
                                <?php unset($_SESSION['mensagem']); ?>
                            <?php endif; ?>
                            
                            <?php if ($pergunta): ?>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $pergunta['id']; ?>">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>Pergunta:</label>
                                            <input type="text" name="pergunta" value="<?php echo htmlspecialchars($pergunta['pergunta']); ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Respostas (separadas por vírgula):</label>
                                            <input type="text" name="respostas" value="<?php echo htmlspecialchars(implode(', ', $pergunta['respostas'])); ?>" required>
                                            <small style="color: #666;">Exemplo: Resposta A, Resposta B, Resposta C</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Resposta correta:</label>
                                            <input type="text" name="correta" value="<?php echo htmlspecialchars($pergunta['correta']); ?>" required>
                                            <small style="color: #666;">Digite exatamente uma das respostas acima</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button type="submit">Salvar Alterações</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <?php else: ?>
                                <p>Pergunta não encontrada ou não é do tipo múltipla escolha.</p>
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