<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use UserStock\Model\UserStock;
use UserStock\Model\UserStockTable;
use Stock\Model\StockTable;
use Zend\View\Model\JsonModel;

// Exceptions
use Application\Exception\NotImplementedException;

/**
 * classe que gerencia o perfil do usuário
 *
 * @api
 * @author Alexsandro André <andre@010minds.com>
 *
 */
class UserExchangeStockRestController extends AbstractRestfulController
{
	protected $userStockTable;
	protected $stockTable;

	/**
     * O método getList faz retorna o perfil do user contendo as informações como:
     * follower,following e lista de followings
     * @return array json_encode
     */
	public function getList()
	{
		return new JsonModel(array(
            'data' => 'getList',
        ));
	}

	/**
     * O método get retorna os dados do user visitado.
     * @return array json_encode
     */
	public function get($id)
	{
		$userId = (int) $this->params()->fromRoute('uid', false);

		return new JsonModel(array(
            'data' => $this->getMyStockByExchange($userId,$id),
        ));
	}

	public function getMyStockByExchange($userId,$id)
	{
		$data    = array();

		//get user_stock
		$results = $this->getUserStockTable()->getStockUser($userId,'');

		foreach ($results as $result) {
			$result->id      = (int) $result->id;
			$result->userId  = (int) $result->userId;
			$result->stockId = (int) $result->stockId;
			$result->value   = (float) $result->value;

			//get stock_exchange
			$resultsStockData = $this->getStockTable()->getCustomStock(array(
				'stock_exchange_id' => $id,
				'id' 				=> $result->stockId,
			));

			//alterando os tipos
			$resultsStockData->id              = (int) $resultsStockData->id;
			$resultsStockData->current         = (float) $resultsStockData->current;
			$resultsStockData->open 		   = (float) $resultsStockData->open;
			$resultsStockData->high 		   = (float) $resultsStockData->high;
			$resultsStockData->low 			   = (float) $resultsStockData->low;
			$resultsStockData->percent 		   = (float) $resultsStockData->percent;
			$resultsStockData->country 		   = (int) $resultsStockData->country;
			$resultsStockData->stockExchangeId = (int) $resultsStockData->stockExchangeId;
			$resultsStockData->volume 		   = (float) $resultsStockData->volume;
			//retirando o exchange
			unset($resultsStockData->exchange);
			$result->stock = $resultsStockData;

			$data[] = $result;
		}

		return $data;
	}

	public function create($data){
		throw new NotImplementedException("This method not exists");
	}

	public function update($id,$data){
		throw new NotImplementedException("This method not exists");
	}

	public function delete($id){
		throw new NotImplementedException("This method not exists");
	}

	public function replaceList($data){
        throw new NotImplementedException("This method not exists");
    }

	/**
     * O método getUserStockTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getUserStockTable()
	{
		if(!$this->userStockTable){
			$sm = $this->getServiceLocator();
			$this->userStockTable = $sm->get('UserStock\Model\UserStockTable');
		}
		return $this->userStockTable;
	}

	/**
     * O método getStockTable faz a selecão da classe table com o banco de dados.
     * @return objeto table
     */
	public function getStockTable()
	{
		if(!$this->stockTable){
			$sm = $this->getServiceLocator();
			$this->stockTable = $sm->get('Stock\Model\StockTable');
		}
		return $this->stockTable;
	}
}