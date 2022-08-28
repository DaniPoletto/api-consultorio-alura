<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EntidadeFactory
     */
    protected $factory;

    /**
     * @var ExtratorDadosRequest
     */
    protected $extratorDadosRequest;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extratorDadosRequest = $extratorDadosRequest;
    }
    
    public function buscarTodos(Request $request) : Response
    {
        $informacoesDeOrdenacao = $this->extratorDadosRequest->buscaDadosOrdenacao($request);
        $informacoesDeFiltro = $this->extratorDadosRequest->buscaDadosFiltro($request);
        [$paginaAtual, $itensPorPagina] = $this->extratorDadosRequest->buscaDadosPaginacao($request);
        
        $EntityList = $this->repository->findBy(
            $informacoesDeFiltro, 
            $informacoesDeOrdenacao,
            $itensPorPagina,
            ($paginaAtual - 1) * $itensPorPagina
        );
        return new JsonResponse($EntityList);
    }

    public function buscarUm(int $id) : Response
    {
        $entity = $this->repository->find($id);

        $codigoRetorno = is_null($entity) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($entity, $codigoRetorno);
    }

    public function remove(int $id) : Response
    {
        // $entity = $this->entityManager->getReference(Especialidade::class, $id);
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entity = $this->factory->criarEntidade($corpoRequisicao);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new JsonResponse($entity);
    }

    public function atualiza(int $id, Request $request) : Response
    {
        $corpoRequisicao = $request->getContent();
        $entityEnviado = $this->factory->criarEntidade($corpoRequisicao);

        $entityExistente = $this->repository->find($id);

        if (is_null($entityExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->atualizarEntidadeExistente($entityExistente, $entityEnviado);

        $this->entityManager->flush();

        return new JsonResponse($entityExistente);
    }

    abstract public function atualizarEntidadeExistente(
        $entityExistente,
        $entityEnviado
    );
}