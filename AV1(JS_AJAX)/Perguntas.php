<?php

define('Perguntas_FILE', 'perguntas.csv');

function carregarPerguntas(){
    $perguntas = [];

    if(file_exists(Perguntas_FILE)){
        $linhas = file(Perguntas_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach($linhas as $linha){
            $separador = explode('|', $linha);
            
       
            $separador = array_values(array_filter($separador, function($value) {
                return $value !== '';
            }));

            $tipo = $separador[0] ?? null;
            $pergunta = $separador[1] ?? '';

            if($tipo == 'mE'){ 

                $respostas = isset($separador[2]) ? explode(',', $separador[2]) : [];
                $correta = $separador[3] ?? '';
                $perguntas[] = [
                    'tipo' => $tipo,
                    'pergunta' => $pergunta,
                    'respostas' => $respostas,
                    'correta' => $correta
                ];
            } else if ($tipo == 'texto') { 

                $perguntas[] = [
                    'tipo' => $tipo,
                    'pergunta' => $pergunta
                ];
            }
        }
    }

    return $perguntas;
}

function salvarPerguntas($perguntas){

    $arq = fopen(Perguntas_FILE, 'a');
    if(!$arq) return "erro ao abrir o arquivo";

    $ultimaIndex = count($perguntas) - 1;
    $p = $perguntas[$ultimaIndex];
    
    if($p['tipo'] == 'mE'){

        $respostas = is_array($p['respostas']) ? implode(',', $p['respostas']) : '';
        
        $linha = '|mE|'.$p['pergunta'].'|'.$respostas.'|'.$p['correta'];
    } else {
        $linha = '|texto|'.$p['pergunta'];
    }

    fwrite($arq, $linha . PHP_EOL);
    
    fclose($arq);
    return "Pergunta salva com sucesso!";
}

function criarPerguntaME(&$perguntas, $pergunta, $respostas, $correta){
    $perguntas[] = [
        'tipo' => 'mE', 
        'pergunta' => $pergunta, 
        'respostas'=> $respostas,
        'correta'=> $correta
    ];
    return salvarPerguntas($perguntas);
}

function criarPerguntaTexto(&$perguntas, $pergunta){
    $perguntas[] = [
        'tipo' => 'texto', 
        'pergunta' => $pergunta
    ];
    return salvarPerguntas($perguntas);
}

function alterarPerguntaMC(&$perguntas, $index, $pergunta, $respostas, $correta){
    if (!isset($perguntas[$index]) || $perguntas[$index]['tipo'] != 'mE') {
        return "Pergunta não encontrada ou tipo incorreto";
    }
    
    $perguntas[$index] = [
        'tipo' => 'mE', 
        'pergunta' => $pergunta, 
        'respostas'=> $respostas,
        'correta'=> $correta
    ];
    
    return sobrescreverPerguntas($perguntas);
}

function alterarPerguntaTexto(&$perguntas, $index, $pergunta){
    if (!isset($perguntas[$index]) || $perguntas[$index]["tipo"] != "texto") {
        return "Pergunta não encontrada ou tipo errado";
    }
    
    $perguntas[$index] = [
        'tipo' => 'texto', 
        'pergunta' => $pergunta
    ];
    
   
    return sobrescreverPerguntas($perguntas);
}

function excluirPergunta(&$perguntas, $index){
    if(isset($perguntas[$index])){
        unset($perguntas[$index]);
        
        $perguntas = array_values($perguntas);
        
        return sobrescreverPerguntas($perguntas);
    } else {
        return "Pergunta não encontrada!";
    }
}


function sobrescreverPerguntas($perguntas){
    $arq = fopen(Perguntas_FILE, 'w');
    if(!$arq) return "erro ao abrir o arquivo";

    foreach($perguntas as $p){
        if($p['tipo'] == 'mE'){
            $respostas = is_array($p['respostas']) ? implode(',', $p['respostas']) : '';
            $linha = '|mE|'.$p['pergunta'].'|'.$respostas.'|'.$p['correta'];
        } else {
            $linha = '|texto|'.$p['pergunta'];
        }

        fwrite($arq, $linha . PHP_EOL);
    }
    
    fclose($arq);
    return "Operação realizada com sucesso!";
}
?>