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
    public function atualizarEntidadeExistente($id, $entityEnviado)
    {
        /** @var Especialidade $entityExistente */
        $entityExistente = $this->repository->find($id);
        if (is_null($entityExistente)) {
            throw new \InvalidArgumentException();
        }
        $entityExistente->setDescricao($entityEnviado->getDescricao());
        
        return $entityExistente; 
    }
}
