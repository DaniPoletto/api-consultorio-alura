<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EntidadeFactory;
use App\Helper\ResponseFactory;
use App\Helper\ExtratorDadosRequest;
use Psr\Cache\CacheItemPoolInterface;
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

    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest,
        CacheItemPoolInterface $cache
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extratorDadosRequest = $extratorDadosRequest;
        $this->cache = $cache;
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

        $fabricaResposta = new ResponseFactory(
            true,
            $EntityList,
            Response::HTTP_OK,
            $paginaAtual,
            $itensPorPagina
        );

        return $fabricaResposta->getResponse();
    }

    public function buscarUm(int $id) : Response
    {
        $entity = $this->cache->hasItem($this->cachePrefix() .  $id)
            ? $this->cache->getItem($this->cachePrefix() . $id)->get()
            : $this->repository->find($id);

        $codigoRetorno = is_null($entity) 
            ? Response::HTTP_NO_CONTENT 
            : Response::HTTP_OK;

        $fabricaResposta = new ResponseFactory(
            true,
            $entity,
            $codigoRetorno
        );

        return $fabricaResposta->getResponse();

        return new JsonResponse($entity, $codigoRetorno);
    }

    public function remove(int $id) : Response
    {
        // $entity = $this->entityManager->getReference(Especialidade::class, $id);
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->cache->deleteItem($this->cachePrefix() . $id);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entity = $this->factory->criarEntidade($corpoRequisicao);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $cacheItem = $this->cache->getItem(
            $this->cachePrefix() . $entity->getId()
        );

        $cacheItem->set($entity);
        $this->cache->save($cacheItem);

        return new JsonResponse($entity);
    }

    public function atualiza(int $id, Request $request) : Response
    {
        $corpoRequisicao = $request->getContent();
        $entityEnviado = $this->factory->criarEntidade($corpoRequisicao);

        try {
            $entityExistente = $this->atualizarEntidadeExistente($id, $entityEnviado);
            $this->entityManager->flush(); 

            $cacheItem = $this->cache->getItem(
                $this->cachePrefix() . $id
            );
    
            $cacheItem->set($entityExistente);
            $this->cache->save($cacheItem);

            $fabrica = new ResponseFactory(
                true,
                $entityExistente,
                Response::HTTP_OK
            );
            return $fabrica->getResponse();
        } catch (\InvalidArgumentException $ex) {
            $fabrica = new ResponseFactory(
                false,
                "Recurso não encontrado",
                Response::HTTP_NOT_FOUND
            );
            return $fabrica->getResponse();
        }
    }

    abstract public function atualizarEntidadeExistente(
        $entityExistente,
        $entityEnviado
    );

    abstract public function cachePrefix(): string;
}