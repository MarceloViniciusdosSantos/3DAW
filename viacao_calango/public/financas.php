<?php
require_once __DIR__.'/../config.php';
$title = 'Finanças';

$currentCity = $_SESSION['city'] ?? "Selecione uma localidade";

include __DIR__.'/views/adminhead.php';
include __DIR__.'/views/financas.php';
include __DIR__.'/views/footer.php';
?>