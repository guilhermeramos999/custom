<?php

class FileWriter
{
    public static function escreveArquivo($arquivoOrig,$arquivoTemp,$novoNum)
    {
        $line = $arquivoOrig->current();
        $lineA = substr($line,0,74) . $novoNum . substr($line,80,160) . "\r\n";

        $arquivoTemp->fwrite($lineA);
    }
}