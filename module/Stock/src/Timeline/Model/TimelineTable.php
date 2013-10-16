<?php
namespace Timeline\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

/**
 * Class TimelineTable Model
 * @api
 * @author Alexsandro Andre <andre@010minds.com>
 */
class TimelineTable{

	/**
     * propriedade que recebe o objeto
     */
	protected $tableGateway;

	/**
     * Construtor
     */
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}

	/**
     * Metodo que lista toda a timeline 
     * @return array $resultSet
     */
	public function fetchAll(){ 
		$resultSet = $this->tableGateway->select(function(Select $select){
			$select->order("date DESC");
		});
		
		return $resultSet;
	}

	/**
     * Metodo que lista uma timeline
     * @param int $id da linha
     * @return array $resultSet
     */ 
	public function getTimeline($id){
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();


		if(!$row){
			throw new \Exception("Could not find row $id");
		}

		return $row;
	}

	/**
	 * Responsável por cadastrar a timeline
	 * @param objeto timeline
	 * @return int $id
	 */
	public function saveTimeline(Timeline $timeline){
		$objDate = new \DateTime('NOW');
		$date    = $objDate->format('Y-m-d H:i:s');
		$data = array(
			'description' => $timeline->description,
			'date' 		  => $date,
			'type' 	      => TypeStatus::getType($timeline->type),
			'user_id' 	  => $timeline->user_id,
		);

		$id = (int) $timeline->id;

		if($id == 0){ 
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		} else {
			if($this->getTimeline($id)){
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception("User id does not exist");
			}
		}

		return $id;
	}

	/**
	 * deleta a timeline
	 * @param int $id id do Timeline a ser deletado
	 */
	public function deleteTimeline($id)	{
		$this->tableGateway->delete(array('id'=>$id));
	}
}