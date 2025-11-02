<?php
session_start();
include "Perguntas.php";

$perguntas = carregarPerguntas();

// Obter mensagem da sessão
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : '';

// Limpar mensagem da sessão
if(isset($_SESSION['mensagem'])) {
    unset($_SESSION['mensagem']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listar Perguntas</title>
    <style>
        .container { width: 90%; margin: 0 auto; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .mensagem { 
            padding: 10px; 
            background-color: #d4edda; 
            color: #155724; 
            border-radius: 4px; 
            margin-bottom: 20px; 
        }
        .error { 
            padding: 10px; 
            background-color: #f8d7da; 
            color: #721c24; 
            border-radius: 4px; 
            margin-bottom: 20px; 
        }
        .actions a { 
            margin-right: 10px; 
            color: #007bff; 
            text-decoration: none; 
        }
        .link {
            color: #007bff;
            text-decoration: none;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Perguntas</h2>
        
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?php echo htmlspecialchars($mensagem); ?></div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Pergunta</th>
                    <th>Respostas</th>
                    <th>Correta</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($perguntas)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Nenhuma pergunta cadastrada</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($perguntas as $pergunta): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pergunta['id']); ?></td>
                        <td><?php echo htmlspecialchars($pergunta['tipo'] == 'mE' ? 'Múltipla Escolha' : 'Texto'); ?></td>
                        <td><?php echo htmlspecialchars($pergunta['pergunta']); ?></td>
                        <td>
                            <?php if($pergunta['tipo'] == 'mE' && !empty($pergunta['respostas'])): ?>
                                <ul>
                                    <?php foreach($pergunta['respostas'] as $resposta): ?>
                                        <li><?php echo htmlspecialchars($resposta); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <?php if($pergunta['tipo'] == 'mE'): ?>
                                <a href="alterar_mc.php?id=<?php echo $pergunta['id']; ?>" class="link">Alterar</a>
                            <?php else: ?>
                                <a href="alterar_texto.php?id=<?php echo $pergunta['id']; ?>" class="link">Alterar</a>
                            <?php endif; ?>
                            <a href="excluir.php?id=<?php echo $pergunta['id']; ?>" 
                            onclick="return confirm('Tem certeza que deseja excluir esta pergunta?')">
                                Excluir
                            </a>
                        </td>
                        <td>
                            <?php if($pergunta['tipo'] == 'mE'): ?>
                                <?php echo htmlspecialchars($pergunta['correta']); ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="excluir.php?id=<?php echo $pergunta['id']; ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir esta pergunta?')">
                                Excluir
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <br>
        <a href="Admin.php" class="link">Voltar ao Menu Admin</a>
    </div>
</body>
</html>