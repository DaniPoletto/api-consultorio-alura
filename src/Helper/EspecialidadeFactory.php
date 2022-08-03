<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFactory
{
    public function criarEspecialidade (string $json) : Especialidade
    {
        $dadoEmJson = json_decode($json);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadoEmJson->descricao);

        return $especialidade;
    }
}