<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <script>
        function ExcluirUsuario(id) {
            if (!confirm('Tem certeza que deseja excluir este usuário?')) {
                return;
            }

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("Resposta: " + this.responseText);
               
                    window.location.href = "listar_user.php?mensagem=" + encodeURIComponent(this.responseText);
                }
            }
            xmlhttp.open("GET", "excluir_user.php?id=" + id);
            xmlhttp.send();
        }
    </script>
    <style>
    
        .container-table { width: 800px; margin: 0 auto; background-color: #f5f5f5; padding: 20px; }
        .content-table { width: 100%; background: white; padding: 30px; border-radius: 8px; }
        .mensagem-table { width: 100%; background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .mensagem-text { color: #155724; font-weight: bold; }
        .main-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .main-table th, .main-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .main-table th { background-color: #007bff; color: white; font-weight: bold; }
        .main-table tr:nth-child(even) { background-color: #f8f9fa; }
        .actions { white-space: nowrap; }
        .actions a { color: #007bff; text-decoration: none; margin: 0 5px; }
        .link { color: #007bff; text-decoration: none; }
        h1, h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <table class="container-table">
        <tr>
            <td>
                <table class="content-table">
                    <tr>
                        <td>
                            <h2>Lista de Usuários</h2>
                            
                            <?php 
                            include "User.php";
                            $usuarios = carregarUser();
                            $mensagem = isset($_GET['mensagem']) ? $_GET['mensagem'] : "";
                            ?>
                            
                            <?php if($mensagem): ?>
                                <table class="mensagem-table">
                                    <tr>
                                        <td class="mensagem-text"><?php echo htmlspecialchars($mensagem); ?></td>
                                    </tr>
                                </table>
                            <?php endif; ?>

                            <?php if ($usuarios): ?>
                                <table class="main-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($usuarios as $id => $usuario): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                                <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                                <td class="actions">
                                                    <a href='javascript:void(0);' onclick="ExcluirUsuario('<?php echo $id; ?>')">Excluir</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>Nenhum usuário cadastrado.</p>
                            <?php endif; ?>

                            <br>
                            <a href="criar_user.php" class="link">Cadastrar Novo Usuário</a> |
                            <a href="Admin.php" class="link">Voltar ao Menu Admin</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>