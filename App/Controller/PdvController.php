<?php
namespace App\Controller;
use App\Model\Pdv;

class PdvController extends Pdv{

    public function inserir($data) 
    {
        return json_encode($this->inserirPdv($data));
    }
    
    public function buscar($data = null) 
    {
        return json_encode($this->buscarPdv($data));
    }
    
}