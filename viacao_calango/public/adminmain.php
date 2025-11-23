<?php
require_once __DIR__.'/../config.php';
$title = 'Admin';

session_start();

// Lista de localidades
$cities = [
    "ac" => "Acre",
    "al" => "Alagoas",
    "ap" => "Amapá",
    "am" => "Amazonas",
    "ba" => "Bahia",
    "ce" => "Ceará",
    "df" => "Distrito Federal",
    "es" => "Espírito Santo",
    "go" => "Goiás",
    "ma" => "Maranhão",
    "mt" => "Mato Grosso",
    "ms" => "Mato Grosso do Sul",
    "mg" => "Minas Gerais",
    "pa" => "Pará",
    "pb" => "Paraíba",
    "pr" => "Paraná",
    "pe" => "Pernambuco",
    "pi" => "Piauí",
    "rj" => "Rio de Janeiro",
    "rn" => "Rio Grande do Norte",
    "rs" => "Rio Grande do Sul",
    "ro" => "Rondônia",
    "rr" => "Roraima",
    "sc" => "Santa Catarina",
    "sp" => "São Paulo",
    "se" => "Sergipe",
    "to" => "Tocantins"
];

// Se o usuário selecionou uma cidade nova
if (isset($_GET['city']) && isset($cities[$_GET['city']])) {
    $_SESSION['city'] = $_GET['city'];
}

// Cidade atual
$currentKey = $_SESSION['city'] ?? null;
$current = $currentKey && isset($cities[$currentKey]) ? $cities[$currentKey] : "Selecione uma localidade";

include __DIR__.'/views/adminmain.php';
include __DIR__.'/views/footer.php';
?>