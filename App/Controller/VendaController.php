<?php
namespace App\Controller;
use App\Model\Venda;
use App\Model\Limite;

class VendaController extends Venda{

    public function inserir($data) 
    {
        if (!empty($data)) {
            $produtos = $this->produtos($data);
            $produtos['id_pdv'] = $data['id_pdv'];
            $produtos['data_venda'] = date('Y-m-d');
            $limite = new Limite();
            $descontar_limite = $limite->alterarLimite($produtos);
            if (!is_string($descontar_limite)) {
                $retorno = $this->inserirVenda($produtos);
                if ($retorno) {
                    return json_encode("Venda realidada com sucesso. Seu novo Limite é de {$descontar_limite}");

                } else {
                    $voltar_limite = $limite->somarLimite($produtos);
                    return json_encode("Erro ao cadastrar venda! Limite não alterado {$voltar_limite}");
                }
            } else {
                return json_encode($descontar_limite);
            }
        } else {
            return false;
        }
    }
    
    public function cancelar($data) 
    {
        $cancelar = $this->cancelarVenda($data);
        if (is_array($cancelar)) {
            $limite = new Limite();
            $voltar_limite = $limite->somarLimite($cancelar);
            return json_encode("Venda cancelada com sucesso! Limite atual {$voltar_limite}");
        } else {
            return json_encode("Venda não encontrada ou obteve algum erro no cancelamento!");
        }
    }
    
    public function buscar($data = null) 
    {
        return json_encode($this->buscarVenda($data));
    }

    public function produtos($data)
    {
        if (!empty($data['id_produto']) || !empty($data['desc_produto'])) {
            $produto = !empty($data['id_produto']) ? $data['id_produto'] : $data['desc_produto'];
            $resposta = file_get_contents("https://api.redeconekta.com.br/mockprodutos/produtos");
            $resposta = json_decode($resposta, true);
            if (!empty($resposta) && is_array($resposta)) {
                foreach ($resposta as $value) {
                    if (in_array($produto, $value)) {
                        $venda = $value;
                    }
                }
                if (!empty($venda)){
                    return $venda;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
}