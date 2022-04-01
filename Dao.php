<?php


class Dao
{
    public function __construct()
    {
        $this->con = new PDO(
            'mysql:localhost;dbname:fntmap',
            'root',
            'Guilherme10@'
        );
        return $this->con;
    }

    public function begin()
    {
        $this->con->beginTransaction();
    }
    public function commit()
    {
        $this->con->commit();
    }
    public function rollback()
    {
        $this->con->rollBack();
    }

    public function insertData($nomeArq,$numPag,$novoNum,$dataVencimento,$valor)
    {
        $this->con->exec(
            "INSERT INTO fntmap.custom (nome_arquivo,numero_origem,numero_novo,data_vencimento,valor) 
            VALUES ('$nomeArq','$numPag','$novoNum','$dataVencimento','$valor');
        ");
    }
    public function searchData($numPag,$dataVencimento,$valor)
    {
        $selectStmt = $this->con->query(
            "SELECT numero_origem,data_vencimento,valor,numero_novo FROM fntmap.custom 
            WHERE numero_origem = '$numPag' AND data_vencimento = '$dataVencimento' AND valor = '$valor';
        ");

        $result = $selectStmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;

    }
}