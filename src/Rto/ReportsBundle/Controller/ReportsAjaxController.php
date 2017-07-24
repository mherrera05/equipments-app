<?php

namespace Rto\ReportsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rto\CoreBundle\APIs\ProjectsFunctions;
use Rto\CoreBundle\APIs\ReportsFunctions;


class ReportsAjaxController extends Controller
{
	public function send($array)
	{
		$reply = new Response(json_encode($array));
		return $reply;
	}
    public function equipmentsProjectAction()
    {
    	$request		 = $this -> getRequest() -> request;
		$type			 = '';
		$data['project'] = $request -> get('project');
		
		$get = ReportsFunctions::getEquipmentsStatus($data, $this);
		if($get['state']){
			$data    = $get['data'];
			$project = $get['project'];
		}
		$array = array('state'=>true, 'type'=>$type, 'data' => $data, 'project' => $project);
		return $this -> send($array);
    }
}
