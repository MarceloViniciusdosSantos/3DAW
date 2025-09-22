<?php

define('Perguntas_FILE', 'perguntas.csv');

function carregarPerguntas(){
    $pergunta = [];

    if(file_exists(Perguntas_FILE)){
        $Linhas = file(Perguntas_FILE);
        foreach($Linhas as $Linha){
            $separador = explode('|', $Linha);
            $id = $separador[0];
            $tipos = $separador[1];
            $pergunta = $separador[2];
            if($tipo = 'mE'){ //mE == Multipla escolha
                 $respostas = explode(',', $separador[3]);
                $correta = $separador[4];
                $perguntas[$id] = ['id'=>$id,'tipo'=>$tipo,'pergunta'=>$pergunta,'respostas'=>$respostas,'correta'=>$correta];
            }else{
                $respostas = $separador[3];
                $perguntas[$id] = ['id'=>$id,'tipo'=>$tipo,'pergunta'=>$pergunta,'respostas'=>$respostas];
            }
        }   
    }
    return $perguntas;
}

function salvarPerguntas($perguntas){
    $arq = fopen(Perguntas_FILE,'a');
    if(!$arq) return "erro ao abrir o arquivo";

    foreach($perguntas as $p){
        if($p['tipo'] == 'mE'){
            $Linhas[] = $p['id'].'|mE|'.$p['pergunta'].'|'.implode(',',$p['respostas']).'|'.$p['correta'];
        }else{
            $Linhas[] = $p['id']. '|texto|'. $p['pergunta'];
        }
         fwrite($arq, $Linhas . PHP_EOL);
         fclose($arq);
    }
}
function criarPerguntaME(&$perguntas, $pergunta, $resposta, $correta){
    $perguntas[] = ['tipo' => 'mE', 'pergunta' => $pergunta, 'resposta'=> $resposta,'correta'=> $correta];
    salvarPerguntas($perguntas);
    return "Pergunta criada !";
}

function criarPerguntaTexto(&$perguntas, $pergunta, $resposta){
    $perguntas[] = ['tipo' => 'texto', 'pergunta' => $pergunta, 'resposta' => $resposta];
    return "Pergunta criada!";
}
function alterarPerguntaME(&$perguntas, $index, $pergunta, $resposta, $correta){
    if (!isset($perguntas[$index]) || $perguntas[$index]['tipo'] != 'mc') return "Pergunta não encontrada ou tipo incorreto";
    $perguntas[$index]= ['tipo' => 'mE', 'pergunta' => $pergunta, 'resposta'=> $resposta,'correta'=> $correta];
    salvarPerguntas($perguntas);
    return "Pergunta alterada! ";
}
function alterarPerguntaTexto(&$perguntas, $index, $pergunta, $resposta){
    if (!isset($perguntas[$index]) || $perguntas[$index]["tipo"] != "texto") return "Pergunta não encontrada ou tipo errado";
    $perguntas[$index] = ['tipo' => 'texto', 'pergunta' => $pergunta, 'resposta' => $resposta];
    return "Pergunta alterada!";
}

function excluirPergunta($perguntas, $index){
    if(isset($perguntas[$index])){
        unset($perguntas[$index]);
        salvarPerguntas($perguntas);
        return "Pergunta excluida! ";
    }else return "Pergunta não encontrada! ";
}
?>
