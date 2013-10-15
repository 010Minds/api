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
		$id   = (int) $id;
		$data = array();

		$results = $this->getTimelineTable()->fetchAllUser($id);

		foreach ($results as $result) {
			$result->id      = (int) $result->id;
			$result->type    = (int) $result->type;
			$result->user_id = (int) $result->user_id;

			$data[] = $result;
		}

		return new JsonModel(array(
            'data' => $data,
        ));
	}

	public function delete($id){
		#code...
	}

	public function create($data){
		$data['user_id'] =  (int) $this->params()->fromRoute('id', false);
		
		$form     = new TimelineForm();
	    $timeline = new Timeline();

	    $form->setInputFilter($timeline->getInputFilter());
	    $form->setData($data);

	    $id = 0;
	    if ($form->isValid()) { 
	        $timeline->exchangeArray($form->getData());
	        $id = $this->getTimelineTable()->saveTimeline($timeline);
	    }

		return new JsonModel(array(
            'data' => $this->getTimelineTable()->getTimeline($id),
        ));
	}

	public function getTimelineTable(){
		if(!$this->timelineTable){
			$sm = $this->getServiceLocator();
			$this->timelineTable = $sm->get('Timeline\Model\TimelineTable');
		}
		return $this->timelineTable;
	}
}