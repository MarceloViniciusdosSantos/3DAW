<?php
include "Perguntas.php";
$perguntas = carregarPerguntas();
$index = isset($_GET['index']) ? intval($_GET['index']) : -1;
$pergunta = ($index >= 0 && isset($perguntas[$index])) ? $perguntas[$index] : null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ver Pergunta</title>
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
            padding: 30px;
            border-radius: 8px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table th, .info-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .info-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            width: 30%;
        }
        .info-table tr:nth-child(even) {
            background-color: #f8f9fa;
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
                            <h2>Ver Pergunta</h2>

                            <?php if ($pergunta): ?>
                                <table class="info-table">
                                    <tr>
                                        <th>ID:</th>
                                        <td><?php echo $index; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tipo:</th>
                                        <td>
                                            <?php 
                                                if ($pergunta['tipo'] == 'mE') {
                                                    echo 'Múltipla Escolha';
                                                } else {
                                                    echo 'Texto';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Pergunta:</th>
                                        <td><?php echo htmlspecialchars($pergunta['pergunta']); ?></td>
                                    </tr>
                                    <?php if ($pergunta['tipo'] == 'mE'): ?>
                                        <tr>
                                            <th>Opções de Resposta:</th>
                                            <td>
                                                <?php if (!empty($pergunta['respostas'])): ?>
                                                    <?php echo implode(", ", $pergunta['respostas']); ?>
                                                <?php else: ?>
                                                    <em>Nenhuma opção definida</em>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Resposta Correta:</th>
                                            <td><?php echo htmlspecialchars($pergunta['correta']); ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <th>Tipo de Resposta:</th>
                                            <td><em>Resposta livre (texto)</em></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            <?php else: ?>
                                <p>Pergunta não encontrada.</p>
                            <?php endif; ?>

                            <br>
                            <a href="listar.php" class="link">Voltar à Lista de Perguntas</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>