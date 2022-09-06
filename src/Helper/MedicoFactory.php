<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory implements EntidadeFactory
{
    /**
     * @var EspecialidadeRepository
     */
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarEntidade(string $json) : Medico
    {
        $dadoEmJson = json_decode($json);

        $this->checkAllProperties($dadoEmJson);

        $especialidadeID = $dadoEmJson->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeID);

        $medico = new Medico();
        $medico
            ->setCrm($dadoEmJson->crm)
            ->setNome($dadoEmJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }

    private function checkAllProperties(object $dadoEmJson): void
    {
        if (!\property_exists($dadoEmJson, 'nome')) {
            throw new EntityFactoryException("Médico precisa de nome");
        }

        if (!\property_exists($dadoEmJson, 'crm')) {
            throw new EntityFactoryException("Médico precisa de crm");
        }

        if (!\property_exists($dadoEmJson, 'especialidadeId')) {
            throw new EntityFactoryException("Médico precisa de especialidade");
        }
    }
}