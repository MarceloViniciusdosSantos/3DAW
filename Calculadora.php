<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo "<br> é post";

    $var1 = $_POST["A"];
$var2 = $_POST["B"];
$op = $_POST ["opcao"];
$result = 0;

if($op == "+"){
    $result = $var1 + $var2;
}elseif($op == "-"){
    $result = $var1 - $var2;
}elseif($op == "*"){
    $result = $var1 * $var2;
}elseif($op == "/"){
    if($var2!=0){
    $result = $var1 / $var2;
} else{
        $result = "ERRO: DIVISÃO POR ZERO!!";
    }
    } else{
        $result = "ERRO: Operador deve ser valído!! (use +, -, *, /)";
        }
}
else{
   echo "<br> é get";
}

?>

<html>
    <head>
        <title> calculadora</title>
    </head>
    <body>
        <?php 
        echo "<br><br>= ". $result;
        ?>

        <form action="Calculadora.php" method="Post" name="CALCULADORA">
            <label for="A"> Valor1: </label>
            <input type="text" id="A" name="A" required><br><br>
            <label for="B"> Valor2: </label>
            <input type="text" id="B" name="B" required><br><br>

            <label for="opcao"> Operação: </label>
            <input type="text" id="opcao" name="opcao" required> <br><br>

            <input type="submit" value="opcao">
    </body>
</html>