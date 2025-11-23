<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
    body {
        margin: 0;
        background: #000;
        font-family: Arial, sans-serif;
        color: #fff;
    }

    .top-bar {
        padding: 12px;
        font-size: 14px;
        position: relative;
        width: fit-content;
        cursor: pointer;
        user-select: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 24px;
        left: 0;
        background: #222;
        border: 1px solid #444;
        min-width: 200px;
        z-index: 10;
    }

    .dropdown-menu a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #fff;
    }

    .dropdown-menu a:hover {
        background: #444;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .container {
        width: 100%;
        height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .grid {
        display: grid;
        grid-template-columns: 200px 200px;
        grid-gap: 30px;
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 60px;
        background: #5c4600;
        border-radius: 10px;
        text-decoration: none;
        color: #000;
        font-weight: bold;
    }



    /* --- OVERLAY FUNCIONÁRIOS --- */

    #overlay-funcionarios {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.75);
        display: none; /* escondido por padrão */
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .overlay-box {
        background: #3a3a3a;
        padding: 45px 60px;
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .overlay-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #5c4600;
        color: #000;
        padding: 15px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        width: 200px;
    }

</style>
</head>

<body>

<div class="top-bar">
    <div class="dropdown">
        <?= htmlspecialchars($current) ?> ▼
        <div class="dropdown-menu">
            <?php foreach ($cities as $key => $name): ?>
                <a href="?city=<?= $key ?>"><?= htmlspecialchars($name) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="grid">
        <a class="btn" href="#" onclick="abrirOverlay()">FUNCIONARIOS</a>
        <a class="btn" href="viarec.php">VIAGENS</a>
        <a class="btn" href="onibus.php">ÔNIBUS</a>
        <a class="btn" href="financas.php">FINANÇAS</a>
    </div>
</div>


<div id="overlay-funcionarios">
    <div class="overlay-box">
        <a class="overlay-btn" href="motorista.php">MOTORISTA</a>
        <a class="overlay-btn" href="supervisores.php">SUPERVISOR</a>
    </div>
</div>


<script>
function abrirOverlay() {
    document.getElementById('overlay-funcionarios').style.display = 'flex';
}

document.getElementById('overlay-funcionarios')
.addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});
</script>

</body>
</html>
