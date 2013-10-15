<?php
namespace Timeline\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Timeline\Model\Timeline;
use Timeline\Model\TimelineTable;
use Timeline\Form\TimelineForm;
use Zend\View\Model\JsonModel;

class TimelineRestController extends AbstractRestfulController{

	protected $timelineTable;

	public function getList(){
		#code...
	}

	public function get($id){
		return new JsonModel(array(
            'data' => $id,
        ));
	}

	public function delete($id){
		#code...
	}

	public function create($id){
		#code...
	}

	public function getTimelineTable(){
		if(!$this->timelineTable){
			$sm = $this->getServiceLocator();
			$this->timelineTable = $sm->get('Timeline\Model\TimelineTable');
		}
		return $this->timelineTable;
	}
}