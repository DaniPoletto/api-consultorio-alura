<?php

namespace App\Helper;

use App\Entity\Especialidade;
use App\Helper\EntityFactoryException;

class EspecialidadeFactory implements EntidadeFactory
{
    public function criarEntidade(string $json) : Especialidade
    {
        $dadoEmJson = json_decode($json);

        if (!\property_exists($dadoEmJson, 'descricao')) {
            throw new EntityFactoryException("Especialidade precisa de descrição");
        }

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadoEmJson->descricao);

        return $especialidade;
    }
}