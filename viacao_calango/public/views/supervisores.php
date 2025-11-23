<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">

<style>
    body {
        margin: 0;
        background: #000;
        color: #fff;
        font-family: Arial, sans-serif;
    }

    .header {
        display: flex;
        justify-content: space-between;
        padding: 15px 25px;
        font-size: 18px;
    }

    .adm-btn {
        background: #5c4600;
        padding: 7px 15px;
        border-radius: 10px;
        text-decoration: none;
        color: #000;
        font-weight: bold;
    }

    .map-container {
        margin: auto;
        margin-top: 40px;
        width: 530px;
        height: 590px;
        background: #111;
        border-radius: 20px;
        padding: 20px;
        position: relative;
    }

    .map-container .mapa {
        width: 100%;
    }

    .icon {
        position: absolute;
        width: 40px;
        height: 40px;
        cursor: pointer;
    }

    /* Overlay */
    #overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.7);
        display: none;
        justify-content: center;
        align-items: center;
    }

    .card {
        background: #f0c23b;
        padding: 25px;
        width: 300px;
        border-radius: 10px;
        color: #000;
    }

    .card img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
    }

</style>
</head>

<body>

<div class="map-container">
    <h2>Supervisores</h2>
    <img src="<?php echo $assets_path; ?>/assets/images/mapa.png" class="mapa"/>

    <img src="<?php echo $assets_path; ?>/assets/images/prof.png" class="icon" style="top:200px; left:150px;" onclick="abrirCard(1)">
    <img src="<?php echo $assets_path; ?>/assets/images/prof.png" class="icon" style="top:250px; left:420px;" onclick="abrirCard(2)">
    <img src="<?php echo $assets_path; ?>/assets/images/prof.png" class="icon" style="top:450px; left:350px;" onclick="abrirCard(3)">
</div>

<div id="overlay">
    <div class="card" id="card-content"></div>
</div>

<script>
const supervisores = {
    1: {
        nome: "Nathan Uilson",
        idade: 28,
        lucro: "R$56.000/mês",
        salario: "R$9.000,00",
        foto: "<?php echo $assets_path; ?>/assets/images/prof2.png"
    },
    2: {
        nome: "Jéssica Lima",
        idade: 34,
        lucro: "R$48.000/mês",
        salario: "R$8.500,00",
        foto: "<?php echo $assets_path; ?>/assets/images/prof2.png"
    },
    3: {
        nome: "Fernando Araújo",
        idade: 41,
        lucro: "R$40.000/mês",
        salario: "R$7.500,00",
        foto: "<?php echo $assets_path; ?>/assets/images/prof2.png"
    }
};

function abrirCard(id) {
    const data = supervisores[id];

    document.getElementById('card-content').innerHTML = `
        <h3>${data.nome}</h3>
        <img src="${data.foto}">
        <p>Idade: ${data.idade}</p>
        <p>Lucro no mês: ${data.lucro}</p>
        <p>Salário: ${data.salario}</p>
    `;

    document.getElementById('overlay').style.display = 'flex';
}

document.getElementById('overlay').addEventListener('click', e => {
    if (e.target === e.currentTarget)
        e.currentTarget.style.display = 'none';
});
</script>

</body>
</html>
