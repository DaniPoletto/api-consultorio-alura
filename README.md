# API Consultório Alura
Projeto implementado durante curso de API com Symfony 5.4 e PHP 7.3.5

[Documentação do Symfony](https://symfony.com/doc/5.4/setup.html)

### Para criar um projeto no symfony para api
`` composer create-project symfony/skeleton:"^5.4" my_project_directory ``

### Para inicializar o servidor
`` php -S localhost:8080 -t public ``

### Para instalar o pacote de anotações
``composer require annotation``

Automaticamente anotações serão interpretadas como rotas. 

Exemplo: A rota /ola executa o método olaMundoAction() que faz parte de uma classe criada na pasta de Controller.
```
    /**
     * @Route("/ola")
     */
    public function olaMundoAction ()
    {
        echo 'Olá , mundo!';
        exit();
    }
```

Para retornar em JSON
```
    /**
     * @Route("/ola")
     */
    public function olaMundoAction ()
    {
        return new JsonResponse(['mensagem' => 'Olá, mundo!']);
    }
```
