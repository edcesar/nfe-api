<?php
namespace NFe\Entity\Imposto\COFINS;

class GenericoTest extends \PHPUnit_Framework_TestCase
{
    private $resource_path;

    protected function setUp()
    {
        $this->resource_path = dirname(dirname(dirname(dirname(__DIR__)))).'/resources';
    }

    public function testGenericoXML()
    {
        $cofins_generico = new \NFe\Entity\Imposto\COFINS\Generico();
        $cofins_generico->setValor(3.50);
        $cofins_generico->fromArray($cofins_generico);
        $cofins_generico->fromArray($cofins_generico->toArray());
        $cofins_generico->fromArray(null);

        $xml = $cofins_generico->getNode();
        $dom = $xml->ownerDocument;

        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/cofins/testGenericoXML.xml');
        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));

        // $dom->formatOutput = true;
        // file_put_contents(
        //     $this->resource_path . '/xml/imposto/cofins/testGenericoXML.xml',
        //     $dom->saveXML($xml)
        // );
    }

    public function testGenericoLoadXML()
    {
        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/cofins/testGenericoXML.xml');

        $cofins_generico = new \NFe\Entity\Imposto\COFINS\Generico();
        $cofins_generico->loadNode($dom_cmp->documentElement);

        $xml = $cofins_generico->getNode();
        $dom = $xml->ownerDocument;

        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));
    }
}
