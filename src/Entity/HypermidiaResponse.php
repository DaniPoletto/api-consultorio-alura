<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HypermidiaResponse
{
    /**
     * @var bool
     */
    private $success;
    /**
     * @var int
     */
    private $page;
    /**
     * @var int|null
     */
    private $itemsPerPage;
    /**
     * @var mixed
     */
    private $data;
    /**
     * @var int
     */
    private $status;

    public function __construct($data, bool $success = true, int $status = Response::HTTP_OK, ?int $page = 1, ?int $itemsPerPage = null)
    {
        $this->success = $success;
        $this->status = $status;
        $this->page = $success ? $page : null;
        $this->data = $data;
        $this->itemsPerPage = !is_null($page) ? $itemsPerPage : null;
    }

    public static function fromError(\Throwable $erro)
    {
        return new self(['mensagem' => $erro->getMessage()], false, Response::HTTP_INTERNAL_SERVER_ERROR, null);
    }

    public function getResponse(): JsonResponse
    {
        $data = get_object_vars($this);
        unset($data['status']);
        if (is_null($this->page)) {
            unset($data['page']);
            unset($data['itemsPerPage']);
        }

        return new JsonResponse($data, $this->status);
    }
}
