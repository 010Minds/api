<?php
namespace Timeline\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class Timeline Model
 * @api
 * @author Alexsandro Andre <andre@010minds.com>
 */
class Timeline implements InputFilterAwareInterface{
	/**
	 * id from table timeline
	 */
	public $id;
	/**
	 * description action timeline
	 */
	public $description;
	/**
	 * date format timestamp
	 */
	public $date;
	/**
	 * permission buy/Sell
	 */
	public $type;
	/**
	 * User id
	 */
	public $user_id;
	protected $inputFilter;

	/**
	 * Metodo responsavel por iniciar os objetos
	 */
	public function exchangeArray($data){
		$this->id 	       = (!empty($data['id'])) ? $data['id'] : null;
		$this->description = (!empty($data['description'])) ? $data['description'] : null;
		$this->date  	   = (!empty($data['date'])) ? $data['date'] : null;
		$this->type        = (!empty($data['type'])) ? $data['type'] : null;
		$this->user_id     = (!empty($data['user_id'])) ? $data['user_id'] : null;
	}

	/**
	 * Método copia os objetos
	 * @api
	 * @return (object) $_this
	 */
	public function getArrayCopy(){
		$_this = get_object_vars($this);
		unset($_this['inputFilter']);
		return $_this;
	}

	public function setInputFilter(InputFilterInterface $inputFilter){
		throw new \Exception("Not used");
	}

	public function getInputFilter(){
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name'		=> 'user_id',
				'required'	=> false,
				'filters'	=> array(
					array('name' => 'Int'),
				),
			));
			$inputFilter->add(array(
				'name'		=> 'type',
				'required'	=> false,
				'filters'	=> array(
					array('name' => 'Int'),
				),
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}