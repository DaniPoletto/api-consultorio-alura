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

Para informar os métodos permitidos por essa rota
```
    /**
     * @Route("/medicos", methods={"POST"})
     */
```

### Para retornar em JSON
```
    /**
     * @Route("/ola")
     */
    public function olaMundoAction ()
    {
        return new JsonResponse(['mensagem' => 'Olá, mundo!']);
    }
```

### Request e Response
O método do controller recebe uma Requisição HTTP (Request) e uma resposta HTTP (Response) que são classes que devem ser importadas na classe do Controller.
```
    /**
     * @Route("/ola")
     */
    public function olaMundoAction (Request $request) : Response
    {
        $pathInfo = $request->getPathInfo();
        return new JsonResponse([
            'mensagem' => 'Olá, mundo!',
            'pathInfo' => $pathInfo
        ]);
    }
```

Retornando uma resposta:
```
    /**
     * @Route("/medicos")
     */
    public function novo(Request $request) : Response
    {
        $corpoRequisicao = $request->getContent();
        return new Response($corpoRequisicao);
    }
```

Retornando uma resposta em Json:
```
   return new JsonResponse($medico);
```

### Para pegar parametros:

- pega um parametro especifico da query string
```
$parametro = $request->query->get('parametro');
```

- pega todos os parametros da query string
```
$parametros = $request->query->all();
```

- pegar parametros de uma forma genérica
```
$parametro1 = $request->get('parametro')
```

- pegar parametro definido na URL da rota
```
$parametro1 = $request->attributes->get('parametro')
```

- pegar parametro do corpo da requisição
```
$parametro1 = $request->request->get('parametro')
```

[Mais informações](https://symfony.com/doc/current/components/http_foundation.html)

### Retornar o corpo da requisição
```
$corpoRequisicao = $request->getContent();
```

### Instalando pacote de ORM (Doctrine)
```
composer require symfony/orm-pack
```

### Criando o banco
```
php bin\console doctrine:database:create
```


