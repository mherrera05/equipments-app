<?php

namespace Rto\ReportsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Rto\CoreBundle\APIs\ProjectsFunctions;
use Rto\CoreBundle\APIs\ReportsFunctions;


class ReportsController extends Controller
{
    public function equipmentsAction()
    {
    	$ticks = $axis = $installations = $participations = '';
    	$get   = ProjectsFunctions::projects($this);
		if($get['state']){
			$data = $get['data'];
			
			$get = ReportsFunctions::getAllEquipmentsStatus($data, $this);
			if($get['state']){
				$ticks  = $get['ticks'];
				$serie1 = $get['serie1'];
				$serie2 = $get['serie2'];
			}
		}
		$get = ReportsFunctions::getInstallationsOverTime($this);
		if($get['state']){
			$installations = $get['data'];
		}
		$get = ReportsFunctions::getParticipations($this);
		if($get['state']){
			$participations = $get['data'];
		}
        return $this->render('RtoReportsBundle:reports:equipments.html.twig', array('projects' => $data, 'ticks'=> $ticks, 'serie1'=>$serie1, 'serie2' => $serie2, 'installed'=>$installations, 'participations'=>$participations));
    }
}
