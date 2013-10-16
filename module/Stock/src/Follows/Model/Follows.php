<?php
namespace Follows\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class Follows Model
 * @api
 * @author Alexsandro Andre <andre@010minds.com>
 */
class Follows implements InputFilterAwareInterface{
	/**
	 * id from table follows
	 */
	public $id;
	/**
	 * id from user table
	 */
	public $user_id;
	/**
	 * date format timestamp
	 */
	public $created_date;
	/**
	 * permission true/false
	 */
	public $permission;
	/**
	 * following from user table
	 */
	public $following;
	public $user;
	protected $inputFilter;

	/**
	 * Metodo responsavel por iniciar os objetos
	 */
	public function exchangeArray($data){
		$this->id 	    	 = (!empty($data['id'])) ? $data['id'] : null;
		$this->user_id  	 = (!empty($data['user_id'])) ? $data['user_id'] : null;
		$this->created_date  = (!empty($data['created_date'])) ? $data['created_date'] : null;
		$this->following  	 = (!empty($data['following'])) ? $data['following'] : null;
		$this->user          = (!empty($data['user'])) ? $data['user'] : null;
		$this->permission    = (!empty($data['permission'])) ? $data['permission'] : false;
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

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}