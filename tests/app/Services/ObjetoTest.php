<?php
declare(strict_types=1);


namespace Manzano\CvdwCli\Services;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Manzano\CvdwCli\Services\Objeto::class)]
class ObjetoTest extends TestCase
{

    protected InputInterface $input;
    protected OutputInterface $output;

    public function setUp(): void
    {
        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
    }

    public function testRetornarObjetos()
    {
        $objeto = new Objeto($this->input, $this->output);
        $this->assertEquals(OBJETOS, $objeto->retornarObjetos());
    }

    public function testRetornarObjetosEspecifico()
    {
        $objeto = new Objeto($this->input, $this->output);
        $objetoAssert['leads'] = OBJETOS['leads'];
        $this->assertEquals($objetoAssert, $objeto->retornarObjetos('leads'));
    }

    public function testRetornarObjetosNaoExistengte()
    {
        $objeto = new Objeto($this->input, $this->output);
        $this->assertEquals(array(), $objeto->retornarObjetos('leads_xxx'));
    } 

    public function testRetornarObjeto()
    {
        $objeto = new Objeto($this->input, $this->output);
        $arquivoNome = "leads";
        $arquivoCaminho = __DIR__ . "/../../../src/app/Objetos/{$arquivoNome}.yaml";
        $this->assertEquals(
                Yaml::parse(file_get_contents($arquivoCaminho)),
                $objeto->retornarObjeto($arquivoNome)
            );
    }

    public function testObjetoNaoEncontrado()
    {
        $objeto = new Objeto($this->input, $this->output);
        $arquivoNome = "leads_naoexiste";
        $arquivoCaminho = __DIR__ . "/../../../src/app/Objetos/{$arquivoNome}.yaml";
        $this->assertEquals(
            [],
            $objeto->retornarObjeto($arquivoNome)
        );
    }

    public function testIdentificarTipoDeDadosTabela()
    {
        $objeto = new Objeto($this->input, $this->output);
        $this->assertEquals("TABELA",
                            $objeto->identificarTipoDeDados([
                                                                "nome" => "João",
                                                                ["nome" => "Maria", "idade" => 30]
                                                            ]));
    }

    public function testIdentificarTipoDeDadosComponente()
    {
        $objeto = new Objeto($this->input, $this->output);
        $this->assertEquals("COMPONENTE", $objeto->identificarTipoDeDados(["nome" => "João", "idade" => 25]));
    }

}