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

/* grid */
.grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

/* card */
.fin-card {
    background: #111;
    border: 2px solid #333;
    border-radius: 12px;
    padding: 18px;
}

.fin-label {
    font-size: 14px;
    color: #ccc;
    opacity: 0.85;
}

.fin-value {
    font-size: 26px;
    color: #ffd600;
    font-weight: bold;
    margin-top: 6px;
}
</style>
</head>
<body>

<div class="container">

    <div class="title">FINANÇAS</div>

    <div class="grid">

        <div class="fin-card">
            <div class="fin-label">Saldo total disponível</div>
            <div class="fin-value">R$ 128.900,00</div>
        </div>

        <div class="fin-card">
            <div class="fin-label">Gastos mensais</div>
            <div class="fin-value">R$ 32.450,00</div>
        </div>

        <div class="fin-card">
            <div class="fin-label">Receita mensal</div>
            <div class="fin-value">R$ 41.200,00</div>
        </div>

    </div>

</div>

</body>
</html>
