<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

require_once(__DIR__ . '/../api/autoload.php');

$emitente = new Emitente();
$emitente->setRazaoSocial('Empresa LTDA');
$emitente->setFantasia('Minha Empresa');
$emitente->setCNPJ('12345678000123');
$emitente->setTelefone('11955886644');
$emitente->setIE('123456789');
$emitente->setIM('95656');
$emitente->setRegime(EmitenteRegime::SIMPLES);

$endereco = new Endereco();
$endereco->setCEP('01122500');
$endereco->setUF('PR');
$endereco->getMunicipio()
		 ->setNome('Paranavaí')
		 ->setCodigo(123456);
$endereco->setBairro('Centro');
$endereco->setLogradouro('Rua Paranavaí');
$endereco->setNumero('123');

$emitente->setEndereco($endereco);

$xml = $emitente->getNode();
$dom = $xml->ownerDocument;
$dom->formatOutput = true;

header('Content-Type: application/xml');
echo $dom->saveXML($xml);