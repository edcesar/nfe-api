<?php
namespace NFe\Entity\Imposto\ICMS;

class ReducaoTest extends \PHPUnit_Framework_TestCase
{
    private $resource_path;

    protected function setUp()
    {
        $this->resource_path = dirname(dirname(dirname(dirname(__DIR__)))).'/resources';
    }

    public function testReducaoXML()
    {
        $icms_reducao = new \NFe\Entity\Imposto\ICMS\Reducao();
        $icms_reducao->setModalidade(\NFe\Entity\Imposto\ICMS\Normal::MODALIDADE_OPERACAO);
        $icms_reducao->setBase(90.00);
        $icms_reducao->setAliquota(18.0);
        $icms_reducao->setReducao(10.0);
        $icms_reducao->fromArray($icms_reducao);
        $icms_reducao->fromArray($icms_reducao->toArray());
        $icms_reducao->fromArray(null);

        $xml = $icms_reducao->getNode();
        $dom = $xml->ownerDocument;

        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/icms/testReducaoXML.xml');
        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));

        // $dom->formatOutput = true;
        // file_put_contents(
        //     $this->resource_path . '/xml/imposto/icms/testReducaoXML.xml',
        //     $dom->saveXML($xml)
        // );
    }

    public function testReducaoLoadXML()
    {
        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/icms/testReducaoXML.xml');

        $icms_reducao = new \NFe\Entity\Imposto\ICMS\Reducao();
        $icms_reducao->loadNode($dom_cmp->documentElement);

        $xml = $icms_reducao->getNode();
        $dom = $xml->ownerDocument;

        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));
    }
}
