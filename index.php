<?php

require_once 'FileWriter.php';
require_once 'Dao.php';

try{
    
    $dao = new Dao();
    
    $dao->begin();
    
    $nomeArqOrig = 'TesteFol.txt';
    $nomeArqTemp = 'ArquivoTemp.'.uniqid().'.txt';
    
    $arquivoOrig = new SplFileObject($nomeArqOrig,'r+');
    $arquivoTemp = new SplFileObject($nomeArqTemp,'w+');
    
    $pagFolha = false;

    while(!$arquivoOrig->eof()){
        $line = $arquivoOrig->fgets();
        $tipoReg = substr($line,7,1);
        $tipoPag = substr($line,9,2);
        $seg = substr($line,13,1);
    
        $tipoReg == '5' ? $pagFolha = false : false;
    
        if($tipoReg == '1' && $tipoPag == '30'){
            $pagFolha = true;
        }
        if($seg == 'A' && $pagFolha == true){
            $numPag = substr($line,74,6);
            $dataVencimento = substr($line,93,8);
            $valor = substr($line,120,15);
            $novoNum = str_pad(rand(0,999999),6,'0',STR_PAD_LEFT);
    
            $sqlSearch = $dao->searchData($numPag,$dataVencimento,$valor);
    
            if(count($sqlSearch) === 0){
                $dao->insertData($nomeArqOrig,$numPag,$novoNum,$dataVencimento,$valor);
                FileWriter::escreveArquivo($arquivoOrig,$arquivoTemp,$novoNum);
            }else{
                echo "Dados em duplicidade, não inserindo em base e preenchendo o arquivo com o número já existente na tabela custom: {$sqlSearch[0]['numero_novo']}\r\n";
                FileWriter::escreveArquivo($arquivoOrig,$arquivoTemp,$sqlSearch[0]['numero_novo']);
            }
        }else{
            $arquivoTemp->fwrite($line);
        }
    }
    $dao->commit();
}catch(Exception){
    $dao->rollback();
    throw new Exception("Erro - Dados não inseridos em base.");
}

$arquivoTemp = null;
rename($nomeArqTemp, "TesteRename.".uniqid());