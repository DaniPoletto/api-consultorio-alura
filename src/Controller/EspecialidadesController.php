<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Controller\BaseController;
use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
{
    /**
     * @var EspecialidadeFactory
     */
    private $especialidadeFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeFactory $especialidadeFactory,
        EspecialidadeRepository $especialidadeRepository,
        ExtratorDadosRequest $extratorDadosRequest
    ) {
        parent::__construct
        (
            $especialidadeRepository, 
            $entityManager, 
            $especialidadeFactory,
            $extratorDadosRequest
        );
    }

    /**
     * @param Especialidade $entityExistente
     * @param Especialidade $entityEnviado
     */
    public function atualizarEntidadeExistente($entityExistente, $entityEnviado)
    {
        $entityExistente->setDescricao($entityEnviado->getDescricao()); 
    }
}
