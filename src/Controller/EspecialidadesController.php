<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Controller\BaseController;
use App\Helper\EspecialidadeFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class EspecialidadesController extends BaseController
{
    /**
     * @var EspecialidadeFactory
     */
    private $especialidadeFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeFactory $especialidadeFactory,
        EspecialidadeRepository $especialidadeRepository
    ) {
        parent::__construct
        (
            $especialidadeRepository, 
            $entityManager, 
            $especialidadeFactory
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
