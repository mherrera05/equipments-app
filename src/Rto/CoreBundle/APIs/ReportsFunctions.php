<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\Core;
use Rto\CoreBundle\APIs\ProjectsFunctions;

//Entity
use Rto\CoreBundle\Entity\Equipments;

class ReportsFunctions 
{
	public static function getEquipmentsStatus($data, $class)
	{
		$reply['state'] = false;
		$project['id']  = $data['project'];
		$get			= ProjectsFunctions::getProject($project, $class);
		if($get['state']){
			$obProject      = $get['project'];
			$em 	        = $class -> getDoctrine() -> getManager();
			$query			= $em -> createQuery("SELECT COUNT(E) 
												  FROM RtoCoreBundle:projects P,
												  	   RtoCoreBundle:locations L,
												  	   RtoCoreBundle:equipments E
												  WHERE P.id = L.projects
												  AND   L.id = E.locations
												  AND   L.gather = 0
												  AND   P.id = ".$data['project']."");
												 
			$installed      = $query -> getResult();
			
			$query			= $em -> createQuery("SELECT COUNT(E) 
												  FROM RtoCoreBundle:projects P,
												  	   RtoCoreBundle:locations L,
												  	   RtoCoreBundle:equipments E
												  WHERE P.id = L.projects
												  AND   L.id = E.locations
												  AND   L.gather = 1
												  AND   P.id = ".$data['project']."");
			
			$uninstalled   = $query -> getResult();
		}
		$values            = array(array('Installed ('.$installed[0][1].')',$installed[0][1]),array('Uninstalled ('.$uninstalled[0][1].')',$uninstalled[0][1]));
		$reply['data']     = $values;
		$reply['project']  = $obProject -> getName();
		$reply['state']    = true;
		return $reply;
	}
	public static function getAllEquipmentsStatus($data, $class)
	{
		$reply['state'] = false;
		$em 	        = $class -> getDoctrine() -> getManager();
		foreach ($data as $element) {
			$obj            = $element['project'];
			$query			= $em -> createQuery("SELECT COUNT(E) 
												  FROM RtoCoreBundle:projects P,
												  	   RtoCoreBundle:locations L,
												  	   RtoCoreBundle:equipments E
												  WHERE P.id = L.projects
												  AND   L.id = E.locations
												  AND   L.gather = 0
												  AND   P.id = ".$obj -> getId()."");
												 
			$installed      = $query -> getResult();
			if(empty($installed)){
				$installed = 0;
			} 
			
			$query			= $em -> createQuery("SELECT COUNT(E) 
												  FROM RtoCoreBundle:projects P,
												  	   RtoCoreBundle:locations L,
												  	   RtoCoreBundle:equipments E
												  WHERE P.id = L.projects
												  AND   L.id = E.locations
												  AND   L.gather = 1
												  AND   P.id = ".$obj -> getId()."");
			
			$uninstalled   = $query -> getResult();
			if(empty($uninstalled)){
				$uninstalled = 0;
			} 
			
			$ticks[]  = $obj -> getName();
			$serie1[] = $installed[0][1];
			$serie2[] = $uninstalled[0][1];
			$labels   = array();
		}
		
		$reply['ticks']    = $ticks;
		$reply['serie1']   = $serie1;
		$reply['serie2']   = $serie2;
		$reply['state']    = true;
		
		return $reply;
	}
	public static function getInstallationsOverTime($class)
	{
		$reply['state'] = false;
		
		$date 	= new \DateTime('now');
		$em   	= $class -> getDoctrine() -> getManager();
		$query	= $em -> createQuery("SELECT DATE_DIFF('".$date->format("Y-m-d")."', I.date) AS days,E.id as equip, E.serial AS serial, TE.name AS type, M.name AS model, L.name AS location, P.name as project, U.name as Uname, U.lastname as Ulastname 
									  FROM RtoCoreBundle:equipments AS E,
									  	   RtoCoreBundle:locations AS L,
									  	   RtoCoreBundle:equipmentsinstallation AS EI,
									  	   RtoCoreBundle:installations AS I,
									  	   RtoCoreBundle:typeequipments AS TE,
									  	   RtoCoreBundle:users AS U,
									  	   RtoCoreBundle:projects AS P,
									  	   RtoCoreBundle:models AS M
									  WHERE E.locations = L.id 
									  AND   L.gather = 0
									  AND   L.workplace = 0 
									  AND   EI.equipments = E.id 
									  AND   EI.installation = I.id 
									  AND   E.models = M.id 
									  AND   I.users = U.id 
									  AND   M.typeEquipments = TE.id 
									  AND   I.date < '".$date->modify("-15 days") ->format("Y-m-d")."' 
									  GROUP BY E.serial 
									  ORDER BY days DESC");
		
		$installed      = $query -> getResult();
		$reply['data']     = $installed;
		$reply['state']    = true;
		return $reply;
	}
	public static function getParticipations($class)
	{
		$reply['state'] = false;
		$data           = array();
		$moth 			= date("m");
		$em   	= $class -> getDoctrine() -> getManager();
		$query	= $em -> createQuery("SELECT COUNT(L.id) AS times, U.name AS Uname, U.lastname AS Ulastname 
									  FROM RtoCoreBundle:logs AS L,
									  	   RtoCoreBundle:users AS U
									  WHERE L.users = U.id
									  AND   L.date LIKE '%-".$moth."-%' 
									  GROUP BY L.users 
									  ORDER BY times DESC");
		
		$participations    = $query -> getResult();
		$i = 0;/*
		foreach ($participations as $participation) {
			$data[$i] = array($participation['times']rand(1,100), $participation['Uname']." ".$participation['Ulastname'][0].".");
			$i++;
			if($i >= 3){
				break;
			}
		}*/
		$data = array(
			array("65", "Miguel H."),
			array("25", "Usuario 2."),
			array("10", "Usuario 3."));
		$reply['data']     = $data;
		$reply['state']    = true;
		return $reply;
	}
}
