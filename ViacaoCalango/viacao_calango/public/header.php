<?php
require_once __DIR__.'/../../app/Auth.php';
$user = Auth::user();

$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');
$assets_path = str_replace('/public', '', $base_path);
?><!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Viação - <?php echo htmlspecialchars($title ?? ''); ?></title>
  <link rel="stylesheet" href="<?php echo $assets_path; ?>/assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div class="logo">
    <a href="<?php echo $base_path; ?>/index.php">
      <img src="<?php echo $assets_path; ?>/assets/images/logo.png" alt="logo"/>
    </a>
  </div>
  <nav>
    <a href="<?php echo $base_path; ?>/index.php">Início</a>
    <a href="<?php echo $base_path; ?>/viagens.php">Viagens</a>
    <a href="<?php echo $base_path; ?>/sobre.php">Sobre nós</a>
    <a href="<?php echo $base_path; ?>/ajuda.php">Ajuda</a>
  </nav>
  <div class="user-area">
    <?php if($user): ?>
      Olá, <?php echo htmlspecialchars($user['name']); ?> | 
      <a href="<?php echo $base_path; ?>/perfil.php">Perfil</a> | 
      <a href="<?php echo $base_path; ?>/sair.php">Sair</a>
    <?php else: ?>
      <a href="<?php echo $base_path; ?>/login.php">Entrar</a> | 
      <a href="<?php echo $base_path; ?>/cadastro.php">Cadastrar</a>
    <?php endif; ?>
  </div>
</header>
<main class="container">