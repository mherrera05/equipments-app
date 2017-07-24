<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\TypeLocationsFunctions;
use Rto\CoreBundle\APIs\ProjectsFunctions;
use Rto\CoreBundle\APIs\EquipmentsFunctions;
use Rto\CoreBundle\APIs\LogsFunctions;

//Entity
use Rto\CoreBundle\Entity\Locations;

class LocationsFunctions
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$type['id'] = $data['type'];
		$get		= TypeLocationsFunctions::getTypeLocation($type, $class);
		if($get['state']){
			$data['type'] = $get['type'];
		}
		
		$project['id'] = $data['project'];
		$get		   = ProjectsFunctions::getProject($project,$class);
		if($get['state']){
			$data['project'] = $get['project'];
		}else{
			$data['project'] = null;
		}
		
		$location = new Locations();
		$location -> setProjects($data['project']);
		$location -> setTypeLocations($data['type']);
		$location -> setName($data['name']);
		$location -> setLatitude($data['latitude']);
		$location -> setLength($data['length']);
		$location -> setGather($data['gather']);
		$location -> setWorkplace($data['workplace']);

		$em -> persist($location);
		$em -> flush();
		
		$log['title']       = 'New location '.$data['name'].' has been added';
		$log['description'] = 'Location created by ';
		$log['link']        = '';
		
		$do = LogsFunctions::add($log, $class);
		
		$reply['state'] = true;
		return $reply;
	}
	public static function locations($class)
	{
		$reply['state'] = false;
		$locations		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Locations')
								 -> findAll();
		if(!empty($locations)){
			$i = 0;
			foreach($locations as $location){
				$data[$i]['edit'] = $class -> generateUrl('Admin_editLocation', array('id'=>$location -> getId()));
				$data[$i]['location'] = $location;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function getLocation($data, $class)
	{
		$reply['state'] = false;
		$location  		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Locations')
								 -> findOneByid($data['id']);
		if(!empty($location)){
			$reply['state']     = true;
			$reply['location']  = $location;
		}
		return $reply;
	}
	public static function update($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$type['id'] = $data['type'];
		$get		= TypeLocationsFunctions::getTypeLocation($type, $class);
		if($get['state']){
			$data['type'] = $get['type'];
		}
		
		$project['id'] = $data['project'];
		$get		   = ProjectsFunctions::getProject($project,$class);
		if($get['state']){
			$data['project'] = $get['project'];
		}else{
			$data['project'] = null;
		}
		
		$get = LocationsFunctions::getLocation($data, $class);
		if($get['state']){
			$location = $get['location'];
			$location -> setName($data['name']);
			$location -> setProjects($data['project']);
			$location -> setTypeLocations($data['type']);
			$location -> setLatitude($data['latitude']);
			$location -> setLength($data['length']);
			$location -> setGather($data['gather']);
			$location -> setWorkplace($data['workplace']);
			
			$em -> persist($location);
			$em -> flush();
			
			$log['title']       = 'Location '.$data['name'].' has been updated';
			$log['description'] = 'Location updated by ';
			$log['link']        = '';
			
			$do = LogsFunctions::add($log, $class);
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function delete($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = LocationsFunctions::getLocation($data, $class);
		if($get['state']){
			$location = $get['location'];
			
			$log['title']       = 'Location '.$location -> getName().' has been deleted';
			$log['description'] = 'Location deleted by ';
			$log['link']        = '';
			
			$do = LogsFunctions::add($log, $class);
			
			$em   	  -> remove($location);
			$em       -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getLocationsByProject($data, $class)
	{
		$reply['state'] = false;
		$locations 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Locations')
								 -> findByprojects($data['project']); 
		if(!empty($locations)){
			$i    = 0;
			$data = '';
			foreach ($locations as $location) {
				if($location -> getGather() == 0){
					$data[$i] = array('id'=>$location -> getId(), 'name'=>$location -> getName());
					$i++;
				}
			}
			$reply['data']  = $data;
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function locationWithEquipments($class)
	{
		$reply['state'] = false;
		$sql 			= "SELECT DISTINCT locations.name AS locationName, locations.latitude, locations.length, projects.name AS projectName, count(equipments.id) AS count, locations.id AS locationId
							FROM locations, equipments, projects 
						    WHERE locations.id = equipments.locations_id
							AND locations.projects_id = projects.id 
							GROUP BY locations.name";

    	$em = $class -> getDoctrine()
    				 -> getManager()
    				 -> getConnection() 
    				 -> prepare($sql);
    	$em -> execute();
		$locations = $em -> fetchAll();
		
		if(!empty($locations)){
			$reply['data']  = $locations;
			$reply['state'] = true;
		}
		return $reply;	
	}
	public static function locationByTypeEquipments($data, $class)
	{
		$reply['state'] = false;
		$sql 			= "SELECT DISTINCT locations.name AS locationName, locations.latitude, locations.length, projects.name AS projectName, count(equipments.id) AS count, locations.id AS locationId
							FROM locations, equipments, projects, models, type_equipments 
						    WHERE locations.id = equipments.locations_id
							AND locations.projects_id = projects.id 
							AND equipments.models_id = models.id
							AND models.type_equipments_id = type_equipments.id
							AND type_equipments.id = '".$data['id']."'
							GROUP BY locations.name";

    	$em = $class -> getDoctrine()
    				 -> getManager()
    				 -> getConnection() 
    				 -> prepare($sql);
    	$em -> execute();
		$locations = $em -> fetchAll();
		
		if(!empty($locations)){
			$reply['data']  = $locations;
			$reply['state'] = true;
		}
		return $reply;	
	}
	public static function centers($class)
	{
		$reply['state'] = false;
		$locations      = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Locations')
								 -> findBy(array('gather'=> 1));
		if(!empty($locations)){
			$reply['locations'] = $locations;
			$reply['state']     = true;
		}
		return $reply;
	}
	public static function locationWithStaff($class)
	{
		$reply['state'] = false;
		$sql 			= "SELECT DISTINCT(locations.id) AS locationId, locations.name AS locationName, projects.name AS projectName, locations.latitude, locations.length, COUNT(users.id) AS count
							FROM locations, worktime, users, projects
							WHERE locations.workplace = 1 
							AND worktime.locations_id = locations.id
							AND locations.projects_id = projects.id
							AND worktime.end_date IS NULL
							AND worktime.users_id = users.id
							GROUP BY locations.id";

    	$em = $class -> getDoctrine()
    				 -> getManager()
    				 -> getConnection() 
    				 -> prepare($sql);
    	$em -> execute();
		$locations = $em -> fetchAll();
		if(!empty($locations)){
			$reply['data']  = $locations;
			$reply['state'] = true;
		}
		return $reply;
	}
}
