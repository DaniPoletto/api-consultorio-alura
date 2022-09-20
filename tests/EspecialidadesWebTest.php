<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EspecialidadeWebTest extends WebTestCase
{
    public function testGaranteQueRequisicaoFalhaSemAutenticacao()
    {
        $client = static::createClient();

        $client->request('GET', '/especialidades');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testGaranteQueEspecialidadesSaoListadas()
    {
        $client = static::createClient();
        $token = $this->login($client);

        $client->request('GET', '/especialidades', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ]);

        $resposta = json_decode($client->getResponse()->getContent());
        $this->assertTrue($resposta->sucesso);
    }

    private function login(KernelBrowser $client):string
    {
        $client->request('POST', '/login', [], [], 
            [
                'CONTENT_TYPE' => 'application/json'
            ], 
            json_encode([
                'usuario' => 'usuario',
                'senha' => '123456'
            ])
        );

        return json_decode($client->getResponse()->getContent())
            ->access_token;
    }

    public function testInsereEspecialidade()
    {
        $client = static::createClient();
        $token = $this->login($client);

        $client->request('POST', '/especialidades', [], [], [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer $token"
            ], 
            json_encode([
                'descricao' => 'teste'
            ])
        );
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testHtmlEspecialidades()
    {
        $client = self::createClient();
        $client->request('GET', '/especialidades_html');

        $this->assertSelectorTextContains('h1', 'Especialidades');

        $this->assertSelectorExists('.especialidade');
    }
}

