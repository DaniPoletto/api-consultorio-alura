# API Consultório Alura
Projeto implementado durante curso de API com [Symfony 5.4](https://symfony.com/doc/5.4/setup.html) e PHP 7.3.5. 

Consiste em uma api que relaciona médicos a especialidades. 

## Rotas

### Médicos
| Rota | Método | Descrição | BODY PARAMS | 
| --- | --- | --- | --- |
| /medicos | POST | Cadastra um médico | <pre> {<br> "crm": 123456,<br> "nome": "Terceiro médico",<br> "especialidadeId": 2<br>} </pre> |
| /medicos | GET | Retorna todos os médicos | - |
| /medicos/{id} | GET | Retorna médico por id | - |
| /medicos/{id} | PUT | Atualiza médico por id | <pre> {<br> "crm": 123456,<br> "nome": "Terceiro médico",<br> "especialidadeId": 2<br>} </pre> |
| /medicos/{id} | DELETE | Remove médico por id | - |

### Especialidades
| Rota | Método | Descrição | BODY PARAMS | 
| --- | --- | --- | --- |
| /especialidades | POST | Cadastra uma especialidade |  <pre> {<br> "descricao": "Ginecologista" <br>} </pre> |
| /especialidades | GET | Retorna todas as especialidades | - |
| /especialidades/{id} | GET | Retorna especialidade por id | - |
| /especialidades/{id} | PUT | Atualiza especialidade por id |  <pre> {<br> "descricao": "Ginecologista" <br>} </pre> |
| /especialidades/{id} | DELETE | Remove especialidade por id | - |

É possível ordenar os dados, por exemplo:
```
http://localhost:8080/medicos?sort[crm]=ASC&sort[nome]=DESC
```

Também é possível filtrar passando os parâmetros pela url:
```
http://localhost:8080/medicos?crm=123456
```

### Para criar um projeto no symfony para api
`` composer create-project symfony/skeleton:"^5.4" consultorio-alura ``

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

Para passar parâmetros para essa rota
```
    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
```
Esses parâmetros são recuperados no Request.

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

Também é possivel passar um segundo parâmetro pro objeto JsonResponse com o status HTTP. Se esse parâmetro não é informado, automaticamente é enviado um status 200.

Pela classe Response é possível obter status como, por exemplo, 204:

```
Response::HTTP_NO_CONTENT
```

### Request e Response
O método do controller recebe uma Requisição HTTP (Request) e retorna uma resposta HTTP (Response) que são classes que devem ser importadas na classe do Controller.
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

- pega um parametro especifico da query string (parâmetros da URL)
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

Caso a entidade seja obtida por meio do repositório, não há necessidade de usar o método persist pois o doctrine já 'observa' automaticamente essa entidade. 

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
Para utilizar repositórios é preciso fazer a classe de controller estender a classe AbstractController que é do próprio Symfony.
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

### Recebendo parâmetros da rota
É possivel receber como parâmetro do método parâmetros definidos na rota desde que tenham o mesmo nome. 

```
    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function atualiza(int $id, Request $request) : Response
    {
```

### Delete
```
        $this->entityManager->remove($medico);
        $this->entityManager->flush();
```

Ao deletar ou atualizar não é preciso retornar todos os dados pelo repositório. Para melhor performance use:
```
$medico = $this->entityManager->getReference(Medico::class, $id);
```

### Instalar componente maker do Symfony
```
composer require maker
```

Listar tudo o que pode ser feito com maker
```
php bin\console list make
```

### Criar uma entidade com maker
```
php bin\console make:entity
```
Após esse comando, o componente irá perguntar pelo nome, tipo e tamanho dos atributos dessa entidade e irá gerar automaticamente os códigos.

Para finalizar, crie e rode a migration.
```
php bin/console make:migration
```

```
php bin\console doctrine:migrations:migrate
```

Caso a entidade já exista, ao utilizar esse comando de criação de entidade, novos atributos podem ser adicionados.

### Apagar o banco de dados
```
php bin/console doctrine:database:drop
```

### Criar controller
```
php bin\console make:controller
```

### Retornar dados de propriedades privadas
```
class Especialidade implements JsonSerializable
{
        public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'descricao' => $this->getDescricao()
        ];
    }
}
```
Ao implementar a classe JsonSerializable e o método jsonSerialize na classe de entidade Especialidade, o método será chamado ao chamar o metodo json_encode().

