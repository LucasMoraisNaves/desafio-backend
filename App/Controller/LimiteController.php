<?php
namespace App\Controller;
use App\Model\Limite;

class LimiteController extends Limite{

    public function inserir($data) 
    {
        return json_encode($this->inserirLimite($data));
    }
    
    public function buscar($data = null) 
    {
        return json_encode($this->buscarLimite());
    }

    public function pagar($data) 
    {
        return json_encode($this->pagarLimite($data));
    }
    
    
}