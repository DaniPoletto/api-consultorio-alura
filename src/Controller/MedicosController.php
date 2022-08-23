<?php
namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Controller\BaseController;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MedicosController extends BaseController
{
    /**
     * @var MedicoFactory
     */
    private $medicoFactory;

    public function __construct(
        MedicoFactory $medicoFactory,
        MedicoRepository $MedicosRepository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($MedicosRepository, $entityManager, $medicoFactory);
    }

    /**
     * @param Medico $entityExistente
     * @param Medico $entityEnviado
     */
    public function atualizarEntidadeExistente($entityExistente, $entityEnviado)
    {
        $entityExistente
            ->setCrm($entityEnviado->getCrm())
            ->setNome($entityEnviado->getNome())
            ->setEspecialidade($entityEnviado->getEspecialidade());
    }

    /**
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     */
    public function buscaPorEspecialidade(int $especialidadeId) : Response
    {
        $medicos = $this->repository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);

    }

}