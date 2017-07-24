<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\Core;
use Rto\CoreBundle\APIs\EquipmentsFunctions;

//Entity
use Rto\CoreBundle\Entity\Logs;

class LogsFunctions
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$get = Core::getUser($class);
		if($get['state']){
			$user = $get['user'];
		}
		
		$log = new Logs();
		$log -> setTitle($data['title']);
		$log -> setDescription($data['description'].$user -> getName().' '.$user -> getLastname());
		$log -> setDate(date_create(date("Y-m-d H:i:s")));
		$log -> setLink($data['link']);
		$log -> setUsers($user);
		
		$em -> persist($log);
		$em -> flush();
		
		$reply['state'] = true;
		return $reply;
	}
	public static function logs($class)
	{
		$reply['state'] = false;
		$logs 			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Logs')
								 -> findBy(array(), array('id' => 'DESC'));
		if(!empty($logs)){
			$i = 0;
			foreach ($logs as $log) {
				if($i == 0){
					$start   = $log -> getId();
				}
				$data[$i]['timeEvent'] = LogsFunctions::getEventTime($log -> getDate() -> format('Y-m-d H:i:s'));
				$data[$i]['log']       = $log;
				$user['id']			   = $log -> getUsers() -> getId();
				$source 			   = Core::existsPicture($user);
				$data[$i]['image']     = $source['url'];
				$data[$i]['comment']   = '';
				$array = explode('/',$log -> getLink());
				if(count($array) > 6){
					switch($array[5]){
						case 'installations':{
							$installation['id'] = $array[6];
							$get    			= EquipmentsFunctions::getInstallation($installation, $class);
							if($get['state']){
								$installationO 		 = $get['installation'];
								$user['id']          = $installationO -> getUsers() -> getId();
								$source 			 = Core::existsPicture($user);
								
								if($installationO -> getComments()){
									$data[$i]['comment'] = array('image' => $source['url'],'name'=>$installationO -> getUsers() -> getName().' '.$installationO -> getUsers() -> getLastname(), 'comment'=>$installationO -> getComments());
								}
							}
							break;
						}
						case 'uninstallations':{
							$uninstallation['id'] = $array[6];
							$get    			   = EquipmentsFunctions::getUninstallation($uninstallation, $class);
							if($get['state']){
								$uninstallationO     = $get['uninstallation'];
								$user['id']          = $uninstallationO -> getUsers() -> getId();
								$source 			 = Core::existsPicture($user);
								
								if($uninstallationO -> getComments()){
									$data[$i]['comment'] = array('image' => $source['url'],'name'=>$uninstallationO -> getUsers() -> getName().' '.$uninstallationO -> getUsers() -> getLastname(), 'comment'=>$uninstallationO -> getComments());
								}
							}
							break;
						}
					}
				}
				$i++;
			}
			$reply['start'] = $start-20;
			$reply['data']  = $data;
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getEventTime($data)
	{
		$time = strtotime($data);
        $time = time() - $time; // to get the time since that moment

	    $tokens = array (
	        31536000 => 'year',
	        2592000 => 'month',
	        604800 => 'week',
	        86400 => 'day',
	        3600 => 'hour',
	        60 => 'minute',
	        1 => 'second'
	    );

	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	    }
	}
	public static function getLogsToScroll($data, $class)
	{
		$reply['state'] = false;
		$query 			= $class -> getDoctrine()
								 -> getManager()
							     -> createQueryBuilder()
								 -> select('l')
        						 -> from('RtoCoreBundle:Logs', 'l')
        						 -> where('l.id BETWEEN :start AND :end')
       							 -> setParameter('start', $data['start']-20)
        						 -> setParameter('end', $data['start']);

    	$logs = $query -> getQuery() -> getResult();
		$logs = array_reverse($logs);
		if(!empty($logs)){
			$i    = 0;
			$data = '';
			foreach ($logs as $log) {
				if($i == 0){
					$start   = $log -> getId();
				}
				$data[$i]['timeEvent'] = LogsFunctions::getEventTime($log -> getDate() -> format('Y-m-d H:i:s'));
				$data[$i]['reg']       = array('link'=>$log-> getLink(), 'title'=>$log->getTitle());
				$user['id']			   = $log -> getUsers() -> getId();
				$source 			   = Core::existsPicture($user);
				$data[$i]['image']     = $source['url'];
				$data[$i]['comment']   = '';
				$array = explode('/',$log -> getLink());
				if(count($array) > 6){
					switch($array[5]){
						case 'installations':{
							$installation['id'] = $array[6];
							$get    			= EquipmentsFunctions::getInstallation($installation, $class);
							if($get['state']){
								$installationO 		 = $get['installation'];
								$user['id']          = $installationO -> getUsers() -> getId();
								$source 			 = Core::existsPicture($user);
								
								if($installationO -> getComments()){
									$data[$i]['comment'] = array('image' => $source['url'],'name'=>$installationO -> getUsers() -> getName().' '.$installationO -> getUsers() -> getLastname(), 'comment'=>$installationO -> getComments());
								}
							}
							break;
						}
						case 'uninstallations':{
							$uninstallation['id'] = $array[6];
							$get    			   = EquipmentsFunctions::getUninstallation($uninstallation, $class);
							if($get['state']){
								$uninstallationO     = $get['uninstallation'];
								$user['id']          = $uninstallationO -> getUsers() -> getId();
								$source 			 = Core::existsPicture($user);
								
								if($uninstallationO -> getComments()){
									$data[$i]['comment'] = array('image' => $source['url'],'name'=>$uninstallationO -> getUsers() -> getName().' '.$uninstallationO -> getUsers() -> getLastname(), 'comment'=>$uninstallationO -> getComments());
								}
							}
							break;
						}
					}
				}
				$i++;
			}
			$reply['data']  = array('array' => $data, 'start' => $start-20);
			$reply['state'] = true;
		}
		return $reply;
	}
	
}
