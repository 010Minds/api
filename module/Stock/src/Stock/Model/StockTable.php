<?php
namespace Stock\Model;

use Zend\Db\TableGateway\TableGateway;

class StockTable
{
	protected $tableGateway;
	protected $select;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getStock($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();

		if(!$row){
			throw new \Exception("Could not find row $id");
		}

		return $row;
	}

	public function getStockExchange($id){
		$id = (int) $id;
		$resultSet = $this->tableGateway->select(array('stock_exchange_id' => $id ));
		return $resultSet;
	}

	public function saveStock(Stock $stock)
	{
		$data = array(
			'name' 				=> $stock->name,
			'sigla' 			=> $stock->sigla,
			'open' 				=> $stock->open,
			'high' 				=> $stock->high,
			'low'				=> $stock->low,
			'percent' 			=> $stock->percent,
			'url' 				=> $stock->url,
			'country' 			=> $stock->country,
			'updated_date' 		=> $stock->updatedDate,
			'stock_exchange_id'	=> $stock->stockExchangeId,
			'volume' 			=> $stock->volume,

		);
/*
		$id = (int) $stock->id;

		if($id == 0){
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
		} else {
			if($this->getStock($id)){
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception("Stock id does not exist");
			}
		}

		return $id;*/
	}

	public function deleteStock($id)
	{
		$this->tableGateway->delete(array('id'=>$id));
	}
}