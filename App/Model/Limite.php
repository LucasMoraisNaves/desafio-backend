<?php 
namespace App\Model;
use App\Controller\Conexao;

class Limite extends Conexao{

    public function buscarLimite($data = null)
    {
        $where = !empty($data['id_pdv']) ? " AND id_pdv = {$data['id_pdv']}" : "";
        $sql = "SELECT * FROM limite WHERE situacao = 'A' {$where}";
        return $this->returnConsulta($sql);
    }

    public function inserirLimite($data)
    {
        if (!empty($data)) {
            $data['limite_atual'] = !empty($data['limite_atual']) ? $data['limite_atual'] : $data['limite_total'];
            extract($data);
            $buscar = $this->buscarLimite(['id_pdv' => $data['id_pdv']]);
            if (empty($buscar)) {
                $sql = "INSERT INTO limite (id_pdv, limite_total, limite_atual)
                        VALUES('{$id_pdv}', '{$limite_total}', '{$limite_atual}')";
                $retorno = $this->returnAdd($sql);
                if ($retorno && !is_string($retorno)) {
                    $limite = $limite_total > $limite_atual ? $limite_total : $limite_atual;
                    return "Foi inserido um novo limite de {$limite} para o PDV de código {$id_pdv}!";
                } else {
                    return $retorno;
                }
            } else {
                return "Já existe um limite ativo para o PDV de código {$id_pdv}!";
            }
        } else {
            return 'Nenhum dado de cadastro enviado';
        }
    }

    public function alterarLimite($data) 
    {
        if (!empty($data)) {
            extract($data);
            $buscar = $this->buscarLimite(['id_pdv' => $data['id_pdv']]);
            if (!empty($buscar) && $buscar[0]['limite_atual'] >= $valor) {
                $limite_alterado =  $buscar[0]['limite_atual'] - $valor;
                $sql = "UPDATE limite SET limite_atual = {$limite_alterado} WHERE id_pdv = {$data['id_pdv']} AND situacao = 'A'";
                $retorno = $this->returnAdd($sql);
                if (!is_string($retorno)) {
                    return  $limite_alterado;
                } else {
                    return $retorno;
                }
            } else {
                return "Limite não disponivel!";
            }

        }  
    }

    public function somarLimite($data) 
    {
        if (!empty($data)) {
            extract($data);
            $buscar = $this->buscarLimite(['id_pdv' => $id_pdv]);
            if (!empty($buscar)) {
                $limite_alterado =  $buscar[0]['limite_atual'] + $valor;
                $sql = "UPDATE limite SET limite_atual = {$limite_alterado} WHERE id_pdv = {$id_pdv} AND situacao = 'A'";
                $retorno = $this->returnAdd($sql);
                if (!is_string($retorno)) {
                    return  $limite_alterado;
                } else {
                    return $retorno;
                }
            } else {
                return "PDV não encontrado!";
            }

        }  
    }

    public function pagarLimite($data) 
    {
        if (!empty($data)) {
            extract($data);
            $buscar = $this->buscarLimite(['id_pdv' => $id_pdv]);
            if (!empty($buscar) && $limite_total >= $buscar[0]['limite_total']){
                $sql = "UPDATE limite SET situacao = 'I' WHERE id_pdv = {$id_pdv} AND situacao = 'A'";
                $retorno = $this->returnAdd($sql);
                if (!is_string($retorno)) {
                    $data['limite_atual'] = $limite_total + $buscar[0]['limite_atual'];
                    return $this->inserirLimite($data);
                } else {
                    return $retorno;
                }
            } else {
                return "Pagamento não autorizado. Valor pago menor que a divida ou não existe nenhuma divida.";
            }
        }
    }
}