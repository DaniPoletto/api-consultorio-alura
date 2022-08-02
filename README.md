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

Antes é preciso alterar as configurações no arquivo .ENV


### Erro 'The controller for URI "/medicos" is not callable'
Inclua o código ao arquivo config/services.yaml
```
public: true
```

### Criando um gerenciador de entidade
Utilizando injeção de dependência é criado um gerenciador de entidade que irá fazer as operações necessárias no banco.

A classe EntityManagerInterface necessita ser importada.
```
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
```

### Persist e Flush

O método persist 'observa' a entidade que recebe como parametro até que seja utilizado o método flush para de fato persistir as alterações no banco. 

As alterações são mapeadas em memória otimizando a performance da aplicação.

```
        $this->entityManager->persist($medico);
        $this->entityManager->flush();
```

### Usando anotações para definir informações das colunas da tabela a ser criada
```
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
     public $id;
```
No código acima é informado que o atributo id é um campo do tipo identificador com valor gerado automaticamente e no formato inteiro.

Para informar um formato texto (char, varchar, text) basta fazer da seguinte forma:
```
@ORM\Column(type="string")
```

### Gerar migration verificando se há diferenças entre o banco e o que foi mapeado para atualizar o banco
```
php bin\console doctrine:migrations:diff
```


### Rodar todas as migrations
```
php bin\console doctrine:migrations:migrate
```


[Mais informações/resumos sobre doctrine](https://github.com/DaniPoletto/doctrine)

### Repositórios
Para utilizar repositórios é preciso fazer a classe de controller extender a classe AbstractController que é do próprio Symfony.
```
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedicosController extends AbstractController
{
    ...
```

### Retorna um repositório (de médicos)
```
$repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
```

### Retorna todos os médicos com esse repositório
```
$medicoList = $repositorioDeMedicos->findAll();
```


