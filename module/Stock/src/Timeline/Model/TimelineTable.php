<?php
namespace Timeline\Model;

use Zend\Db\TableGateway\TableGateway;

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
     * Metodo que lista toda a timeline do usuário
     * @param int $id da user
     * @return array $resultSet
     */
	public function fetchAllUser($id){
		$id        = (int) $id;
		$resultSet = $this->tableGateway->select(array('user_id' => $id));

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
		$data = array(
			'description' => $timeline->description,
			'date' 		  => $timeline->date,
			'type' 	      => $timeline->type,
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