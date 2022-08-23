<?php

namespace App\Helper;

interface EntidadeFactory
{
    public function criarEntidade(string $json);
}