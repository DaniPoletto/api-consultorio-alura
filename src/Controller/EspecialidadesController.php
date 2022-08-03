<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EspecialidadeFactory
     */
    private $especialidadeFactory;

    /**
     * @var EspecialidadeRepository
     */
    private $especialidadeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeFactory $especialidadeFactory,
        EspecialidadeRepository $especialidadeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->especialidadeFactory = $especialidadeFactory;
        $this->especialidadeRepository = $especialidadeRepository;
    }

    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function nova(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $especialidade = $this->especialidadeFactory->criarEspecialidade($corpoRequisicao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades", methods={"GET"})
     */
    public function buscarTodos() : Response
    {
        $especialidadeList  = $this->especialidadeRepository->findAll();

        return new JsonResponse($especialidadeList);
    }

    public function buscaEspecialidade(int $id) 
    {
        $especialidade = $this->especialidadeRepository->find($id);
        return $especialidade;
    }

    public function buscaEspecialidadeReference(int $id)
    {
        $especialidade = $this->entityManager->getReference(Especialidade::class, $id);
        return $especialidade;
    }

    /**
     * @Route("/especialidades/{id}", methods={"GET"})
     */
    public function buscarUm(int $id) : Response
    {
        $especialidade = $this->buscaEspecialidade($id);

        $codigoRetorno = is_null($especialidade) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($especialidade, $codigoRetorno);
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     */
    public function atualiza(int $id, Request $request) : Response
    {
        $corpoRequisicao = $request->getContent();
        $especialidadeEnviado = $this->especialidadeFactory->criarEspecialidade($corpoRequisicao);

        $especialidadeExistente = $this->buscaEspecialidade($id);

        if (is_null($especialidadeExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $especialidadeExistente->setDescricao($especialidadeEnviado->getDescricao()); 

        $this->entityManager->flush();

        return new JsonResponse($especialidadeExistente);
    }

    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     */
    public function remove(int $id) : Response
    {
        $especialidade = $this->buscaEspecialidadeReference($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
