<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFactory implements EntidadeFactory
{
    public function criarEntidade(string $json) : Especialidade
    {
        $dadoEmJson = json_decode($json);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadoEmJson->descricao);

        return $especialidade;
    }
}