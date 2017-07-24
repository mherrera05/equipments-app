<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\LogsFunctions;
use Rto\CoreBundle\APIs\Core;

//Entity
use Rto\CoreBundle\Entity\Worktime;

class WorktimeFunctions 
{
	public static function getWorktime($data, $class)
	{
		$reply['state'] = false;
		$worktime 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Worktime')
								 -> findOneBy(array('users'=> $data['id'], 'endDate'=> NULL));
		if(!empty($worktime)){
			if($worktime -> getLocations() == NULL){
				$data = array('location' => 'At Home', 'project'=> 'None' );
			}else
			{
				$data = array('location' => $worktime -> getLocations() -> getName(), 'project'=> $worktime -> getLocations() -> getProjects() -> getName());
			}
			$reply['data'] = $data;
		}
		$reply['state'] = true;
		return $reply;
	}
	public static function getTimeWorking($data, $class)
	{
		$reply['state'] = false;
		$worktimes 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Worktime')
								 -> findBy(array('users'=>$data['id']), array('id' => 'DESC'));
								
		if(!empty($worktimes)){
			$time = 0;
			foreach ($worktimes as $worktime) {
				if($worktime -> getLocations() != NULL){
					if($worktime -> getEndDate() != NULL){
						$time = $time + (strtotime($worktime -> getEndDate() -> format("Y-m-d H:i:s")) - strtotime($worktime -> getStartDate() -> format("Y-m-d H:i:s")));
					}else{
						$time = $time + (strtotime(date("Y-m-d H:i:s")) - strtotime($worktime -> getStartDate() -> format("Y-m-d H:i:s")));
					}
				}else{
					break;
				}
			}
			$days = intval(($time / 86400));
			if($days > 1){
				$string = $days.' days worked';
			}else{
				$string = $days.' day worked';
			}
			$data = array('timeWorked' => $string);
			$reply['data'] = $data;
			$reply['days'] = $days;
		}
		$reply['state'] = true;
		return $reply;
	} 
	public static function record($data, $class)
	{
		$reply['state'] = false;
		$worktimes		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Worktime')
								 -> findBy(array('users'=> $data['id']), array('startDate'=> 'DESC'));
		if(!empty($worktimes)){
			$i    = 0;
			$data = '';
			foreach ($worktimes as $worktime) {
				$time = 0;
				if($worktime -> getEndDate() == NULL){
					$date1 = strtotime($worktime -> getStartDate() -> format("Y-m-d H:i:s"));
					$date2 = strtotime(date("Y-m-d H:i:s"));
					$time  = $date2-$date1;
				}else{
					$date1 = strtotime($worktime -> getStartDate() -> format("Y-m-d H:i:s"));
					$date2 = strtotime($worktime -> getEndDate() -> format("Y-m-d H:i:s"));
					$time  = $date2-$date1;
				}
				$data[$i] = array('worktime'=>$worktime, 'days' => intval($time/86400));
				$i++;
			}
			$reply['records'] = $data;
			$reply['state'] = true;
		}
		return $reply;
	}
}
