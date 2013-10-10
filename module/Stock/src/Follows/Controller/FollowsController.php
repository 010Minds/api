<?php
namespace Follows\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Follows\Model\Follows;

/**
 * Class Controller
 * @api
 * @author Alexsandro Andre <andre@010minds.com>
 */
class FollowsController extends AbstractActionController{
	/**
     * propriedade que recebe o objeto
     */
	protected $followsTable;

	/**
     * Lista todos os follows 
     * @api
     */
	public function indexAction(){
		return new ViewModel(array(
			'follows' => $this->getfollowsTable()->fetchAll(),
		));
	}

	/**
     * O método responsavel por pegar o objeto da table follows
     * @api
     * @return (object) $this->followsTable
     */
	public function getfollowsTable(){
        if(!$this->followsTable){
            $sm = $this->getServiceLocator();
            $this->followsTable = $sm->get('Follows\Model\FollowsTable');
        }
        return $this->followsTable;
    }
}