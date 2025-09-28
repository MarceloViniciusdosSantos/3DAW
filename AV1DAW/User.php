
<?php
define('Usuarios_FILE', 'usuarios.csv');

function carregarUser() {
    if (!file_exists(Usuarios_FILE)) {
        return [];
    }
    $user = [];
    $arq = fopen(Usuarios_FILE, 'r');
    while (($dados = fgetcsv($arq)) !== FALSE) {
        $user[$dados[0]] = [
            'id' => $dados[0],
            'nome' => $dados[1]
        ];
    }
    fclose($arq);
    return $user;
}

function salvarUser($user){
    $arq = fopen(Usuarios_FILE, 'w');
    foreach ($user as $usuario) {
        fputcsv($arq, $usuario);
}
    fclose($arq);
}

function criarUser(&$usuario, $id, $nome){
    if(isset($usuario[$id]))
        return "O usuario com esse id ja foi cadastrado";
    else{
        $usuario[$id] = ['id' => $id,'nome'=> $nome];
        salvarUser($usuario);
        return "Usuario cadastrado com sucesso";
    }
}

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $usuarios = carregarUser();
    $mensagem = criarUser($usuarios, $id, $nome);

    header("Location: ".$_SERVER['PHP_SELF']."?mensagem=".urlencode($mensagem));
    exit(); 
}

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> jogo corporativo</title>
</head>

<body>

<h2>Cadastrar Usuário</h2>
<form method="POST">
    <label>ID:</label><br>
    <input type="text" name="id" required><br><br>

    <label>Nome:</label><br>
    <input type="text" name="nome" required><br><br>

    <button type="submit">Cadastrar</button>
</form>

</body>

<?php
if (isset($_GET['mensagem'])) {
    echo "<p>".htmlspecialchars($_GET['mensagem'])."</p>";
}
?>
