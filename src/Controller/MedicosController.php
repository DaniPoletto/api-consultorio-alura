<?php
namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Controller\BaseController;
use App\Helper\ExtratorDadosRequest;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends BaseController
{
    /**
     * @var MedicoFactory
     */
    private $medicoFactory;

    public function __construct(
        MedicoFactory $medicoFactory,
        MedicoRepository $MedicosRepository,
        EntityManagerInterface $entityManager,
        ExtratorDadosRequest $extratorDadosRequest
    ) {
        parent::__construct(
            $MedicosRepository, 
            $entityManager, 
            $medicoFactory,
            $extratorDadosRequest
        );
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