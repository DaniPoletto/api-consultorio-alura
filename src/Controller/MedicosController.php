<?php
namespace App\Controller;

use App\Entity\Medico;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request) : Response
    {
        $corpoRequisicao = $request->getContent();
        $dadoEmJson = json_decode($corpoRequisicao);

        $medico = new Medico();
        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function buscarTodos() : Response
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);

        $medicoList = $repositorioDeMedicos->findAll();

        return new JsonResponse($medicoList);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function buscarUm(Request $request) : Response
    {
        $id = $request->get('id');

        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);

        $medico = $repositorioDeMedicos->find($id);

        return new JsonResponse($medico);
    }
}