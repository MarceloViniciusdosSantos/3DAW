<?php
include "Perguntas.php";
$perguntas = carregarPerguntas();
$mensagem = isset($_GET['mensagem']) ? $_GET['mensagem'] : "";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas</title>
    <style>
        .container-table {
            width: 1200px;
            margin: 0 auto;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .content-table {
            width: 100%;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }
        .mensagem-table {
            width: 100%;
            background: #d4edda;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin: 10px 0;
        }
        .mensagem-text {
            color: #155724;
            font-weight: bold;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .main-table th, .main-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .main-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .main-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .actions {
            white-space: nowrap;
        }
        .actions a {
            color: #007bff;
            text-decoration: none;
            margin: 0 5px;
        }
        .link {
            color: #007bff;
            text-decoration: none;
        }
        h1, h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
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
                            <h2>Lista de Perguntas</h2>
                            
                            <?php if($mensagem): ?>
                                <table class="mensagem-table">
                                    <tr>
                                        <td class="mensagem-text"><?php echo htmlspecialchars($mensagem); ?></td>
                                    </tr>
                                </table>
                            <?php endif; ?>

                            <?php if ($perguntas): ?>
                                <table class="main-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tipo</th>
                                            <th>Pergunta</th>
                                            <th>Opções/Respostas</th>
                                            <th>Resposta Correta</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($perguntas as $i => $p): ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <?php 
                                                        if ($p['tipo'] == 'mE') {
                                                            echo 'Múltipla Escolha';
                                                        } else {
                                                            echo 'Texto';
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($p['pergunta']); ?></td>
                                                <td>
                                                    <?php if ($p['tipo'] == 'mE' && !empty($p['respostas'])): ?>
                                                        <?php echo implode(", ", $p['respostas']); ?>
                                                    <?php else: ?>
                                                        <em>Pergunta de texto</em>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($p['tipo'] == 'mE'): ?>
                                                        <?php echo htmlspecialchars($p['correta']); ?>
                                                    <?php else: ?>
                                                        <em>Resposta livre</em>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="actions">
                                                    <a href='ver.php?index=<?php echo $i; ?>'>Ver</a> | 
                                                    <a href='excluir.php?index=<?php echo $i; ?>' onclick="return confirm('Tem certeza que deseja excluir esta pergunta?')">Excluir</a>
                                                    <?php if ($p['tipo'] == 'mE'): ?>
                                                        | <a href='alterar_mc.php?index=<?php echo $i; ?>'>Alterar</a>
                                                    <?php else: ?>
                                                        | <a href='alterar_texto.php?index=<?php echo $i; ?>'>Alterar</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>Nenhuma pergunta cadastrada.</p>
                            <?php endif; ?>

                            <br>
                            <a href="Admin.php" class="link">Voltar ao Menu Admin</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>