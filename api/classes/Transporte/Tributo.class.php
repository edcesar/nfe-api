<?php
/**
 * MIT License
 * 
 * Copyright (c) 2016 MZ Desenvolvimento de Sistemas LTDA
 * 
 * @author Francimar Alves <mazinsw@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */
namespace Transporte;
use Imposto;
use DOMDocument;
use Util;
use Municipio;

/**
 * ICMS retido do Transportador
 */
class Tributo extends Imposto {

	private $servico;
	private $cfop;
	private $municipio;

	public function __construct($tributo = array()) {
		parent::__construct($tributo);
	}

	public function getServico($normalize = false) {
		if(!$normalize)
			return $this->servico;
		return Util::toCurrency($this->servico);
	}

	public function setServico($servico) {
		$this->servico = $servico;
		return $this;
	}

	public function getCFOP($normalize = false) {
		if(!$normalize)
			return $this->cfop;
		return $this->cfop;
	}

	public function setCFOP($cfop) {
		$this->cfop = $cfop;
		return $this;
	}

	public function getMunicipio() {
		return $this->municipio;
	}

	public function setMunicipio($municipio) {
		$this->municipio = $municipio;
		return $this;
	}

	public function toArray() {
		$tributo = parent::toArray();
		$tributo['servico'] = $this->getServico();
		$tributo['cfop'] = $this->getCFOP();
		$tributo['municipio'] = $this->getMunicipio();
		return $tributo;
	}

	public function fromArray($tributo = array()) {
		if($tributo instanceof Tributo)
			$tributo = $tributo->toArray();
		else if(!is_array($tributo))
			return $this;
		parent::fromArray($tributo);
		$this->setServico($tributo['servico']);
		$this->setCFOP($tributo['cfop']);
		$this->setMunicipio($tributo['municipio']);
		if(is_null($this->getMunicipio()))
			$this->setMunicipio(new Municipio());
		return $this;
	}

	public function getNode($name = null) {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$element = $dom->createElement(is_null($name)?'retTransp':$name);
		$element->appendChild($dom->createElement('vServ', $this->getServico(true)));
		$element->appendChild($dom->createElement('vBCRet', $this->getBase(true)));
		$element->appendChild($dom->createElement('pICMSRet', $this->getAliquota(true)));
		$element->appendChild($dom->createElement('vICMSRet', $this->getValor(true)));
		$element->appendChild($dom->createElement('CFOP', $this->getCFOP(true)));
		if(is_null($this->getMunicipio()))
			return $element;
		$municipio = $this->getMunicipio();
		$municipio->checkCodigos();
		$element->appendChild($dom->createElement('cMunFG', $municipio->getCodigo(true)));
		return $element;
	}


	public function loadNode($element, $name = null) {
		$name = is_null($name)?'retTransp':$name;
		if($element->tagName != $name) {
			$_fields = $element->getElementsByTagName($name);
			if($_fields->length == 0)
				throw new Exception('Tag "'.$name.'" do Tributo não encontrada', 404);
			$element = $_fields->item(0);
		}
		$_fields = $element->getElementsByTagName('vServ');
		if($_fields->length > 0)
			$servico = $_fields->item(0)->nodeValue;
		else
			throw new Exception('Tag "vServ" do campo "Servico" não encontrada no Tributo', 404);
		$this->setServico($servico);
		$_fields = $element->getElementsByTagName('vBCRet');
		if($_fields->length > 0)
			$base = $_fields->item(0)->nodeValue;
		else
			throw new Exception('Tag "vBCRet" do campo "Base" não encontrada no Tributo', 404);
		$this->setBase($base);
		$_fields = $element->getElementsByTagName('pICMSRet');
		if($_fields->length > 0)
			$aliquota = $_fields->item(0)->nodeValue;
		else
			throw new Exception('Tag "pICMSRet" do campo "Aliquota" não encontrada no Tributo', 404);
		$this->setAliquota($aliquota);
		$_fields = $element->getElementsByTagName('CFOP');
		if($_fields->length > 0)
			$cfop = $_fields->item(0)->nodeValue;
		else
			throw new Exception('Tag "CFOP" do campo "CFOP" não encontrada no Tributo', 404);
		$this->setCFOP($cfop);
		$_fields = $element->getElementsByTagName('cMunFG');
		$municipio = null;
		if($_fields->length > 0) {
			$municipio = new Municipio();
			$municipio->setCodigo($_fields->item(0)->nodeValue);
		}
		$this->setMunicipio($municipio);
		return $element;
	}

}