<?php 
namespace App\Model;
use App\Controller\Conexao;

class Venda extends Conexao{

    public function buscarVenda($data)
    {
        $where = !empty($data['get_id']) ? " AND id = {$data['get_id']}" : "";
        $sql = "SELECT * FROM vendas  WHERE situacao = 'A' {$where}";
        return $this->returnConsulta($sql);
    }

    public function inserirVenda($data)
    {
        if (!empty($data)) {
            extract($data);
            $sql = "INSERT INTO vendas (id_pdv, id_produto, desc_produto, valor, data_venda)
                    VALUES({$id_pdv}, {$id}, '{$descricao}', '{$valor}', '{$data_venda}')";
            return $this->returnAdd($sql);
            
        } else {
            return 'Nenhum dado de cadastro enviado';
        }
    }

    public function cancelarVenda($data)
    {
        if (!empty($data)) {
            extract($data);
            $buscar = $this->buscarVenda(['get_id' => $id]);
            if (!empty($buscar)) {
                $sql = "UPDATE vendas SET situacao = 'I', observacao = '{$observacao}' WHERE id = {$id} AND situacao = 'A'";
                $retorno =  $this->returnAdd($sql);
                if (!is_string($retorno)) {
                    return $buscar[0];
                } else {
                    return $retorno;
                }
            } else {
                return false;
            }

        }
    }
}