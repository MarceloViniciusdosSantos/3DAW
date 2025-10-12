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
        fputcsv($arq, [$usuario['id'], $usuario['nome']]);
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

function excluirUser(&$usuarios, $id){
    if(isset($usuarios[$id])){
        unset($usuarios[$id]);
        salvarUser($usuarios);
        return "Usuário excluído com sucesso!";
    } else {
        return "Usuário não encontrado!";
    }
}
?>