<?php
namespace NFe\Entity\Imposto\ICMS\Simples;

class CobrancaTest extends \PHPUnit_Framework_TestCase
{
    private $resource_path;

    protected function setUp()
    {
        $this->resource_path = dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/resources';
    }

    public function testCobrancaXML()
    {
         // TODO: verificar vICMSST = 12.96
        $icms_cobranca = new \NFe\Entity\Imposto\ICMS\Simples\Cobranca();
        $icms_cobranca->getNormal()->setModalidade(\NFe\Entity\Imposto\ICMS\Normal::MODALIDADE_OPERACAO);
        $icms_cobranca->getNormal()->setBase(1036.80);
        $icms_cobranca->getNormal()->setAliquota(1.25);
        $icms_cobranca->setModalidade(\NFe\Entity\Imposto\ICMS\Parcial::MODALIDADE_AGREGADO);
        $icms_cobranca->setBase(162.00);
        $icms_cobranca->setMargem(100.00);
        $icms_cobranca->setReducao(10.00);
        $icms_cobranca->setAliquota(18.00);
        $icms_cobranca->fromArray($icms_cobranca);
        $icms_cobranca->fromArray($icms_cobranca->toArray());
        $icms_cobranca->fromArray(null);

        $xml = $icms_cobranca->getNode();
        $dom = $xml->ownerDocument;

        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/icms/simples/testCobrancaXML.xml');
        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));

        // $dom->formatOutput = true;
        // file_put_contents(
        //     $this->resource_path . '/xml/imposto/icms/simples/testCobrancaXML.xml',
        //     $dom->saveXML($xml)
        // );
    }

    public function testCobrancaLoadXML()
    {
        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/icms/simples/testCobrancaXML.xml');

        $icms_cobranca = new \NFe\Entity\Imposto\ICMS\Simples\Cobranca();
        $icms_cobranca->loadNode($dom_cmp->documentElement);

        $xml = $icms_cobranca->getNode();
        $dom = $xml->ownerDocument;

        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));
    }
}
