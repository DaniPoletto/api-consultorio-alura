<?php

namespace App\Helper;

use App\Entity\Medico;

class MedicoFactory
{
    public function criarMedico (string $json) : Medico
    {
        $dadoEmJson = json_decode($json);

        $medico = new Medico();
        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        return $medico;
    }
}