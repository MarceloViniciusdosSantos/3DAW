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

.container {
    padding: 20px;
}

.title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 18px;
}

/* Card */
.trip-card {
    background: #111;
    border: 2px solid #333;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 20px;
}

.card-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}

.label {
    color: #ddd;
    font-size: 13px;
    opacity: 0.85;
}

.value {
    font-size: 15px;
    font-weight: bold;
    color: #ffd600;
}
</style>
</head>
<body>

<div class="container">

    <div class="title">VIAGENS RECENTES</div>

    <!-- CARD 1 -->
    <div class="trip-card">
        <div class="card-row">
            <div class="label">Motorista:</div>
            <div class="value">Samuel Gomes</div>
        </div>

        <div class="card-row">
            <div class="label">Local:</div>
            <div class="value">Duque de Caxias</div>
        </div>

        <div class="card-row">
            <div class="label">Data:</div>
            <div class="value">12/09/2024</div>
        </div>

        <div class="card-row">
            <div class="label">Ônibus:</div>
            <div class="value">Ônibus 02</div>
        </div>
    </div>

    <!-- CARD 2 -->
    <div class="trip-card">
        <div class="card-row">
            <div class="label">Motorista:</div>
            <div class="value">Fábio Souza</div>
        </div>

        <div class="card-row">
            <div class="label">Local:</div>
            <div class="value">Nova Iguaçu</div>
        </div>

        <div class="card-row">
            <div class="label">Data:</div>
            <div class="value">09/09/2024</div>
        </div>

        <div class="card-row">
            <div class="label">Ônibus:</div>
            <div class="value">Ônibus 10</div>
        </div>
    </div>

</div>

</body>
</html>