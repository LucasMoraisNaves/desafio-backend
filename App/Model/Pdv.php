<?php 
namespace App\Model;
use App\Controller\Conexao;

class Pdv extends Conexao{

    public function buscarPdv($data = null)
    {
        $where = !empty($data['get_id']) ? "WHERE id = {$data['get_id']}" : "";
        $sql = "SELECT * FROM pdv {$where}";
        return $this->returnConsulta($sql);
    }

    public function inserirPdv($data)
    {
        if (!empty($data)) {
            extract($data);
            $sql = "INSERT INTO pdv (nome_fantasia, cnpj, nome_responsavel, celular_responsavel)
                    VALUES('{$nome_fantasia}', '{$cnpj}', '{$nome_responsavel}', '{$celular_responsavel}')";
            $retono = $this->returnAdd($sql);

            if ($retono) {
                return "O PDV {$nome_fantasia} do responsavel {$nome_responsavel} foi cadastrado com sucesso!";
            }
        } else {
            return 'Nenhum dado de cadastro enviado';
        }
    }
}