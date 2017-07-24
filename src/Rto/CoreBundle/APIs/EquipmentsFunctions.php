<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\LogsFunctions;
use Rto\CoreBundle\APIs\ModelsFunctions;
use Rto\CoreBundle\APIs\LocationsFunctions;
use Rto\CoreBundle\APIs\UsersFunctions;
//Entity
use Rto\CoreBundle\Entity\Equipments;
use Rto\CoreBundle\Entity\Installations;
use Rto\CoreBundle\Entity\EquipmentsInstallation;
use Rto\CoreBundle\Entity\Uninstallations;
use Rto\CoreBundle\Entity\EquipmentsUninstallation;

class EquipmentsFunctions
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$model['id'] = $data['model'];
		$get		= ModelsFunctions::getModel($model, $class);
		if($get['state']){
			$data['model'] = $get['model'];
		}
		
		$location['id'] = $data['location'];
		$get		    = LocationsFunctions::getLocation($location, $class);
		if($get['state']){
			$data['location'] = $get['location'];
		}else{
			$data['location'] = NULL;
		}
		
		$user['id'] = $data['user'];
		$get		    = UsersFunctions::getUser($user, $class);
		if($get['state']){
			$data['user'] = $get['user'];
		}
		
		$equipment = new Equipments();
		$equipment -> setModels($data['model']);
		$equipment -> setSerial($data['serial']);
		$equipment -> setMac($data['mac']);
		$equipment -> setHostname($data['hostname']);
		$equipment -> setLocations($data['location']);
		$equipment -> setUsers($data['user']);
		$equipment -> setDate(date_create(date("Y-m-d H:i:s")));
		$equipment -> setState($data['state']);

		$em -> persist($equipment);
		$em -> flush();
		
		$log['title']       = 'New equipment '.$data['serial'].' - '.$data['model'] -> getName().' has been added';
		$log['description'] = 'Equipment created by ';
		$log['link']        = $class -> generateUrl('Equipments_viewEquipment', array('id'=>$equipment -> getId()));
				
		$do = LogsFunctions::add($log, $class);
		
		$reply['state'] = true;
		return $reply;
	}
	public static function equipments($class)
	{
		$reply['state'] = false;
		$equips			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Equipments')
								 -> findAll();
		if(!empty($equips)){
			$i = 0;
			foreach($equips as $equip){
				$data[$i]['edit'] = $class -> generateUrl('Equipments_editEquipment', array('id'=>$equip -> getId()));
				$data[$i]['view'] = $class -> generateUrl('Equipments_viewEquipment', array('id'=>$equip -> getId()));
				$data[$i]['equip'] = $equip;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function getEquipment($data, $class)
	{
		$reply['state'] = false;
		$equip  		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Equipments')
								 -> findOneByid($data['id']);
		if(!empty($equip)){
			$reply['state']     = true;
			$reply['equip']     = $equip;
		}
		return $reply;
	}
	public static function update($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$get = EquipmentsFunctions::getEquipment($data, $class);
		if($get['state']){
			$equipment = $get['equip'];
				
			$model['id'] = $data['model'];
			$get		 = ModelsFunctions::getModel($model, $class);
			if($get['state']){
				$data['model'] = $get['model'];
			}
			
			$location['id'] = $data['location'];
			$get		    = LocationsFunctions::getLocation($location, $class);
			if($get['state']){
				$data['location'] = $get['location'];
			}else{
				$data['location'] = NULL;
			}
			
			$user['id'] = $data['user'];
			$get	    = UsersFunctions::getUser($user, $class);
			if($get['state']){
				$data['user'] = $get['user'];
			}
			
			$equipment -> setModels($data['model']);
			$equipment -> setSerial($data['serial']);
			$equipment -> setMac($data['mac']);
			$equipment -> setHostname($data['hostname']);
			$equipment -> setLocations($data['location']);
			$equipment -> setUsers($data['user']);
			$equipment -> setState($data['state']);
			$equipment -> setDateUpdate(date_create(date("Y-m-d H:i:s")));
	
			$em -> persist($equipment);
			$em -> flush();
			
			$log['title']       = 'Equipment '.$data['serial'].' - '.$data['model'] -> getName().' has been updated';
			$log['description'] = 'Equipment updated by ';
			$log['link']        = $class -> generateUrl('Equipments_viewEquipment', array('id'=>$equipment -> getId()));
					
			$do = LogsFunctions::add($log, $class);
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function delete($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = EquipmentsFunctions::getEquipment($data, $class);
		if($get['state']){
			$equipment = $get['equip'];
			
			$log['title']       = 'Equipment '.$equipment -> getSerial().' - '.$equipment -> getModels() -> getName().' has been deleted';
			$log['description'] = 'Equipment deleted by ';
			$log['link']        = $class -> generateUrl('Equipments_start');
					
			$do = LogsFunctions::add($log, $class);
			
			$em   	  -> remove($equipment);
			$em       -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function searchSerial($data, $class)
	{
		$reply['state'] = true;
		$em 			= $class -> getDoctrine() -> getManager();
		if($data['id'] == 'null'){
			$serial 		= $class -> getDoctrine() 
								     -> getRepository('RtoCoreBundle:Equipments')
									 -> findOneByserial($data['serial']);
		}
		else{
			$serial			= $em -> createQueryBuilder();
			$serial	 		-> select('u')
				   			-> from('RtoCoreBundle:Equipments', 'u')
				   			-> where('u.id != :id')
							-> andWhere('u.serial = :serial')
							-> setParameter('id', $data['id'])
							-> setParameter('serial', $data['serial']);
			$serial 		= $serial -> getQuery() 
									  -> getResult(); 
		}
		if(!empty($serial)){
			$reply['state'] = false;
		}
		return $reply;
	}
	public static function searchMac($data, $class)
	{
		$reply['state'] = true;
		$em 			= $class -> getDoctrine() -> getManager();
		if($data['id'] == 'null'){
			$mac 		= $class -> getDoctrine() 
								 -> getRepository('RtoCoreBundle:Equipments')
								 -> findOneBymac($data['mac']);
		}
		else{
			$mac			= $em -> createQueryBuilder();
			$mac	 		-> select('u')
				   			-> from('RtoCoreBundle:Equipments', 'u')
				   			-> where('u.id != :id')
							-> andWhere('u.mac = :mac')
							-> setParameter('id', $data['id'])
							-> setParameter('mac', $data['mac']);
			$mac 		    = $mac -> getQuery() 
								   -> getResult(); 
		}
		if(!empty($mac)){
			$reply['state'] = false;
		}
		return $reply;
	}
	public static function getEquipmentBySerial($data, $class)
	{
		$reply['state'] = false;
		$equipment 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Equipments')
								 -> findOneByserial($data['serial']);
								 
		if(!empty($equipment)){
			$relation = $class -> getDoctrine()
							   -> getRepository('RtoCoreBundle:EquipmentsInstallation')
							   -> findOneByequipments(array('id'=>$equipment -> getId()), array('id' => 'DESC'));
						   
				$data			   = "";
				$data              = array('id' => $equipment -> getId(), 'model' => $equipment -> getModels() -> getName(), 'type'=> $equipment -> getModels() -> getTypeEquipments() -> getName(), 'serial'=> $equipment -> getSerial());
				$reply['state']    = true;
				$reply['data']     = $data;
				$reply['equip']    = $equipment;
				$reply['relation'] = $relation;
		}
		return $reply;
	}
	public static function getEquipmentsByLocation($data, $class)
	{
		$reply['state'] = false;
		$equips         = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Equipments')
								 -> findByLocations($data['id']);
		$data = '';						 
		if(!empty($equips)){
			$data['count']  = count($equips);
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function installations($class)
	{
		$reply['state'] = false;
		$installations  = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Installations')
								 -> findAll();
								 
		if(!empty($installations)){
			$i = 0;
			foreach ($installations as $installation) {
				$relations  = $class -> getDoctrine()
								     -> getRepository('RtoCoreBundle:EquipmentsInstallation')
								     -> findByinstallation($installation -> getId());
				
				$data[$i]['count']        = count($relations);
				$data[$i]['relations']    = $relations;
				$data[$i]['installation'] = $installation;
				$data[$i]['view'] 		  = $class -> generateUrl('Equipments_viewInstallation', array('id'=>$installation -> getId()));
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		
		return $reply;
	}
	public static function addInstallation($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		$root           = '';
		$location['id'] = $data['location'];
		$get 			= LocationsFunctions::getLocation($location, $class);
		if($get['state']){
			$data['location'] = $get['location'];
		}
		
		$user['id'] = $data['user'];
		$get        = UsersFunctions::getUser($user,$class);
		if($get['state']){
			$data['user'] = $get['user'];
		}
		
		$installation   =  new Installations();
		$installation   -> setDate(date_create(date($data['date'])));
		$installation   -> setDateRegistered(date_create(date("Y-m-d H:i:s")));
		$installation   -> setLocations($data['location']);
		$installation   -> setState($data['state']);
		$installation   -> setUsers($data['user']);
		$installation   -> setComments($data['comments']);
		
		$em -> persist($installation);
		$em -> flush();
		
		foreach ($data['equipments'] as $equip) {
			$equipment['id'] = $equip;
			$get 			 = EquipmentsFunctions::getEquipment($equipment, $class);
			if($get['state']){
				$equipmentO = $get['equip'];
				
				if($data['state'] == 1){
					$equipments[] = $equipmentO;
					$root         = $equipmentO -> getLocations() -> getName();
					$equipmentO -> setLocations($data['location']);
					$em 		-> persist($equipmentO);
					$em  		-> flush();
				}
				
				$relation = new EquipmentsInstallation();
				$relation -> setEquipments($equipmentO);
				$relation -> setInstallation($installation);
				$em 	  -> persist($relation);
				$em 	  -> flush();
			}
		}
		
		$get = Core::getUser($class);
		if($get['state']){
			$user = $get['user'];
		}
		
		$log['title']       = 'New installation has been added';
		$log['description'] = 'Installation created by ';
		$log['link']        = $class -> generateUrl('Equipments_viewInstallation',array('id'=>$installation -> getId()));
				
		$do = LogsFunctions::add($log, $class);
		
		if($data['state'] == 1){
			$log['title']       = 'An installation has been completed';
			$log['description'] = 'Installation completed by ';
			$log['link']        = $class -> generateUrl('Equipments_installations');
					
			$do = LogsFunctions::add($log, $class);
			
			$location['id'] = $data['location'];
			$get            = LocationsFunctions::getLocation($location,$class);
			if($get['state']){
				$location = $get['location'];
				if($location -> getProjects() -> getUsers()){
					$mail['title']       = 'New Installation has been completed by '.$user -> getName().' '.$user -> getLastname();
					$mail['from']        = 'RTOHALL System';
					$mail['email']       = $location -> getProjects() -> getUsers() -> getEmail();
					$mail['copy']        = $data['copy'];
					$mail['view']        = 'RtoCoreBundle:mails:newInstallation.html.twig';
					$mail['data']        = $equipments;
					$mail['responsable'] = $data['user'];
					$mail['location']    = $location -> getName();
					$mail['root']        = $root;
					$mail['comments']    = $data['comments'];
					
					$do = Core::sendMessage($mail, $class);
				}
			}
		}

		$reply['state'] = true;
		return $reply;
	}
	public static function completeInstallation($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		$relations      = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:EquipmentsInstallation')
								 -> findByinstallation($data['id']);
		if(!empty($relations)){
			foreach ($relations as $relation) {
				$equipment    = $relation -> getEquipments();
				$equipments[] = $equipment;
				$root		  = $relation -> getEquipments() -> getLocations() -> getName();
				$installation = $relation -> getInstallation();
				$comments     = $relation -> getInstallation() -> getComments();
				$equipment    -> setLocations($relation -> getInstallation() -> getLocations());
				if($relation -> getInstallation() -> getLocations() -> getProjects() -> getUsers()){
					$email	= $relation -> getInstallation() -> getLocations() -> getProjects() -> getUsers() -> getEmail();
				}
				$location     = $relation -> getInstallation() -> getLocations() -> getName();
				$installation -> setState(1);
				$user['id']   = $installation -> getUsers() -> getId();
				$em -> persist($equipment);
				$em -> flush();
				$em -> persist($installation);
				$em -> flush();
				
				$get        = UsersFunctions::getUser($user,$class);
				if($get['state']){
					$responsable = $get['user'];
				}
				
			}
			
			$log['title']       = 'An installation has been completed';
			$log['description'] = 'Installation completed by ';
			$log['link']        = $class -> generateUrl('Equipments_installations');
					
			$do = LogsFunctions::add($log, $class);
			
			$get = Core::getUser($class);
			if($get['state']){
				$user = $get['user'];
			}
			
			if(!empty($email)){
				$mail['title']       = 'New Installation has been registred by '.$user -> getName().' '.$user -> getLastname();
				$mail['from']        = 'RTOHALL System';
				$mail['email']       = $email;
				$mail['view']        = 'RtoCoreBundle:mails:newInstallation.html.twig';
				$mail['data']        = $equipments;
				$mail['location']    = $location;
				$mail['responsable'] = $responsable;
				$mail['root']        = $root;
				$mail['comments']    = $comments;
						
				$do = Core::sendMessage($mail, $class);
			}
						
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getCountInstallations($class)
	{
		$reply['state']  = false;
		$user 			 = Core::getUser($class);
		$user			 = $user['user'];
		$installations   = $class -> getDoctrine()
								  -> getRepository('RtoCoreBundle:Installations')
								  -> findBy(array('users'=>$user -> getId(), 'state'=>0));
		if(!empty($installations)){
			$count  		= count($installations);
			$reply['count'] = $count;
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function loadDetails($data, $class)
	{
		$reply['state'] = false;
		$equipments     = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Equipments')
								 -> findBylocations($data['id']);
		if(!empty($equipments)){
			$i    			= 0;
			$location['id'] = $data['id'];
			$data 			= '';
			foreach ($equipments as $equipment) {
				$relation = $class -> getDoctrine() 
								   -> getRepository('RtoCoreBundle:EquipmentsUninstallation')
								   -> findOneByequipments(array('id'=>$equipment -> getId()), array('id'=> 'DESC'));
				if(!empty($relation)){
					$related = 1;
				}else{
					if($equipment -> getLocations() -> getId() == $location['id']){
						$related = 1;
					}else{
						$related = 0;
					}
				}				   
				$data[$i] = array('id'=> $equipment -> getId(), 'serial'=>$equipment -> getSerial(), 'model'=> $equipment -> getModels() -> getName(), 'brand'=>$equipment -> getModels() -> getBrands() -> getName(), 'related'=>$related);
				$i++;
			}
			$reply['data']  = $data;
			$reply['state'] = true;
		}					
		return $reply;			 
	}
	public static function uninstallations($class)
	{
		$reply['state']   = false;
		$uninstallations  = $class -> getDoctrine()
								   -> getRepository('RtoCoreBundle:Uninstallations')
								   -> findAll();
								 
		if(!empty($uninstallations)){
			$i = 0;
			foreach ($uninstallations as $uninstallation) {
				$equipments = $class -> getDoctrine()
								     -> getRepository('RtoCoreBundle:EquipmentsUninstallation')
								     -> findByuninstallations($uninstallation -> getId());
				
				$data[$i]['count']           = count($equipments);
				$data[$i]['equipments']      = $equipments;
				$data[$i]['uninstallation']  = $uninstallation;
				$data[$i]['view']            = $class -> generateUrl('Equipments_viewUninstallation',array('id'=>$uninstallation -> getId()));
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function addUninstallation($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine()
								 -> getManager();
		
		$center['id']   = $data['center'];
		$get 			= LocationsFunctions::getLocation($center, $class);
		if($get['state']){
			$data['center'] = $get['location'];
		}
		
		$user['id'] = $data['responsable'];
		$get 		= UsersFunctions::getUser($user, $class);
		if($get['state']){
			$data['responsable'] = $get['user'];
		}
		
		$uninstallation = new Uninstallations();
		$uninstallation -> setDate(date_create(date($data['date'])));
		$uninstallation -> setDateRegistered(date_create(date('Y-m-d H:i:s')));
		$uninstallation -> setState($data['state']);
		$uninstallation -> setUsers($data['responsable']);
		$uninstallation -> setLocations($data['center']);
		$uninstallation -> setComments($data['comments']);
		
		$em -> persist($uninstallation);
		$em -> flush();
		
		$i = 0;
		foreach ($data['equipments'] as $equip) {
			$equipment['id'] = $equip;
			
			$get = EquipmentsFunctions::getEquipment($equipment, $class);
			if($get['state']){
				$equipmentO = $get['equip'];
				
				if($data['state'] == 1){
					$equipments[]   = $equipmentO;
					$location['id'] = $equipmentO -> getLocations() -> getId();
					$equipmentO     -> setLocations($data['center']);
					$em 		    -> persist($equipmentO);
					$em  		    -> flush();
				}
				
				$relation = new EquipmentsUninstallation();
				$relation -> setEquipments($equipmentO);
				$relation -> setUninstallations($uninstallation);
				$em 	  -> persist($relation);
				$em 	  -> flush();
				
				$relations[$i] = $relation;
				$i++;
			}
		}
		$get = Core::getUser($class);
		if($get['state']){
			$user = $get['user'];
		}
		
		$log['title']       = 'New uninstallation has been added';
		$log['description'] = 'Uninstallation created by ';
		$log['link']        = $class -> generateUrl('Equipments_viewUninstallation',array('id'=>$uninstallation -> getId()));
				
		$do = LogsFunctions::add($log, $class);
		
		if($data['state']==1){
			$log['title']       = 'An uninstallation has been completed';
			$log['description'] = 'Uninstallation created by ';
			$log['link']        = $class -> generateUrl('Equipments_uninstallations');
					
			$do = LogsFunctions::add($log, $class);
			
			if($get['state']){
				$get 		= LocationsFunctions::getLocation($location, $class);
				if($get['state']){
					$location = $get['location'];
				}
				if($location -> getProjects() -> getUsers()){
					$mail['title']       = 'New Uninstallation has been registred by '.$user -> getName().' '.$user -> getLastname();
					$mail['from']        = 'RTOHALL System';
					$mail['email']       = $location -> getProjects() -> getUsers() -> getEmail();
					$mail['copy']        = $data['refer'];
					$mail['view']        = 'RtoCoreBundle:mails:newUninstallation.html.twig';
					$mail['data']        = $equipments;
					$mail['responsable'] = $data['responsable'];
					$mail['root']        = $location -> getName();
					$mail['location']	 = $data['center'] -> getName();
					$mail['comments']	 = $data['comments'];
					 
					
					$do = Core::sendMessage($mail, $class);
				}
			}
		}
		
		$reply['state'] = true;
		return $reply;
	}
	public static function completeUninstallation($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		$relations      = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:EquipmentsUninstallation')
								 -> findByuninstallations($data['id']);
		if(!empty($relations)){
			foreach ($relations as $relation) {
				$equipment      = $relation -> getEquipments();
				$equipments[]   = $equipment;
				$uninstallation = $relation -> getUninstallations();
				$comments		= $relation -> getUninstallations() -> getComments();
				if($equipment -> getLocations() -> getProjects() -> getUsers()){
					$email	= $equipment -> getLocations() -> getProjects() -> getUsers() -> getEmail();
				}
				$location        = $equipment -> getLocations() -> getName();
				$equipment      -> setLocations($relation -> getUninstallations() -> getLocations());
				$center 		= $relation -> getUninstallations() -> getLocations() -> getName();
				$uninstallation -> setState(1);
				$user['id']     = $uninstallation -> getUsers() -> getId();
				$em -> persist($equipment);
				$em -> flush();
				$em -> persist($uninstallation);
				$em -> flush();
				
				$get        = UsersFunctions::getUser($user,$class);
				if($get['state']){
					$responsable = $get['user'];
				}
			}
			
			$log['title']       = 'An uninstallation has been completed';
			$log['description'] = 'Uninstallation created by ';
			$log['link']        = $class -> generateUrl('Equipments_uninstallations');
					
			$do = LogsFunctions::add($log, $class);
			
			$get = Core::getUser($class);
			if($get['state']){
				$user = $get['user'];
			}
			
			if(!empty($email)){
				$mail['title']       = 'New Uninstallation has been registred by '.$user -> getName().' '.$user -> getLastname();
				$mail['from']        = 'RTOHALL System';
				$mail['email']       = $email;
				$mail['view']        = 'RtoCoreBundle:mails:newUninstallation.html.twig';
				$mail['data']        = $equipments;
				$mail['root']        = $location;
				$mail['responsable'] = $responsable;
				$mail['location']	 = $center;
				$mail['comments']	 = $comments;
				
				$do = Core::sendMessage($mail, $class);
			}
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getCountUninstallations($class)
	{
		$reply['state']  = false;
		$user 			 = Core::getUser($class);
		$user			 = $user['user'];
		$uninstallations = $class -> getDoctrine()
								  -> getRepository('RtoCoreBundle:Uninstallations')
								  -> findBy(array('users'=>$user -> getId(), 'state'=>0));
		if(!empty($uninstallations)){
			$count  		= count($uninstallations);
			$reply['count'] = $count;
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getManagers($class)
	{
		$reply['state'] = false;
		$managers       = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Users')
								 -> findByrole('ROLE_COORD');
		
		if(!empty($managers)){
			$reply['managers'] = $managers;
			$reply['state'] = true;
		}
		return $reply;		
	}
	public static function getInstallation($data, $class)
	{
		$reply['state'] = false;
		$installation 	= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Installations')
								 -> findOneByid($data['id']);
		if(!empty($installation)){
			$equipments  		   = $class -> getDoctrine()
								 			-> getRepository('RtoCoreBundle:EquipmentsInstallation')
								 			-> findBy(array('installation'=>$installation -> getId()));
			$reply['equipments']   = $equipments;					 
			$reply['installation'] = $installation; 
			$reply['state'] 	   = true;
		}						
		return $reply;
	}
	public static function deleteInstallation($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		$get 			= EquipmentsFunctions::getInstallation($data, $class);
		if($get['state']){
			$installation = $get['installation'];
			
			$em -> remove($installation);
			$em -> flush();
			
			$reply['state'] = true;
		}
		return $reply;		
	}
	public static function getUninstallation($data, $class)
	{
		$reply['state'] = false;
		$uninstallation = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Uninstallations')
								 -> findOneByid($data['id']);
		if(!empty($uninstallation)){
			$equipments  		     = $class -> getDoctrine()
								 			  -> getRepository('RtoCoreBundle:EquipmentsUninstallation')
								 			  -> findBy(array('uninstallations'=>$uninstallation -> getId()));
			$reply['equipments']     = $equipments;		
			$reply['uninstallation'] = $uninstallation;
			$reply['state'] 		 = true;
		}
		return $reply;
	}
	public static function deleteUninstallation($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		
		$get = EquipmentsFunctions::getUninstallation($data, $class);
		if($get['state']){
			$uninstallation = $get['uninstallation'];
			
			$em -> remove($uninstallation);
			$em -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getBoard( $class)
	{
		$reply['state'] = false;
		$em 			  = $class -> getDoctrine() 
								   -> getManager();
		
		$array = $data = '';
		$get   = ProjectsFunctions::projects($class);
		if($get['state']){
			$projects = $get['data'];
			$get      = '';
			foreach ($projects as $element) {
				$project['project'] = $element['project'] -> getId();
				$get                = LocationsFunctions::getLocationsByProject($project, $class);
				if($get['state'] && !empty($get['data'])){
					$locations = $get['data'];
					$get       = '';
					foreach ($locations as $local) {
						$location['id'] = $local['id'];
						$get 			= EquipmentsFunctions::getEquipmentsByLocation($location, $class);
						if($get['state']){
							$equips   = $get['data']['count'];
							$get      = '';
						}else{
							$equips = 0;
						}
						$array[] = array('location'=>$local['name'], 'equips'=>$equips);
					}
					$data[] = array('project'=> $element['project'] -> getName(), 'data'=>$array);
				}
				$array = '';
			}
		}
		$reply['data'] = $data;
		$reply['state'] = true;
		return $reply;
	}
	public static function getReport($data, $class){
		$reply['state']   = false;
		$em 			  = $class -> getDoctrine() 
								   -> getEntityManager();
		$connection 	  = $em    -> getConnection();
		$completeString   = "";
		if(!empty($data['coordinator'])){
			$completeString .= " AND C.id = '".$data['coordinator']."'";
		}
		if(!empty($data['project'])){
			$completeString .= " AND P.id = '".$data['project']."'";
		}
		if(!empty($data['location'])){
			$completeString .= " AND L.id = '".$data['location']."'";
		}
		if(!empty($data['brand'])){
			$completeString .= " AND B.id = '".$data['brand']."'";
		}
		if(!empty($data['type'])){
			$completeString .= " AND T.id = '".$data['type']."'";
		}
		if(!empty($data['model'])){
			$completeString .= " AND M.id = '".$data['model']."'";
		}
		$sql              = "SELECT B.name as brandName, M.name as modelName, T.name as typeName, E.serial as equipSerial, E.mac as equipMac, L.name as location.name, P.name as projectName, E.state as state							
							 FROM    brands AS B,
									 models AS M,
									 type_equipments AS T,
									 equipments AS E,
									 users AS C, 
									 locations AS L,
									 projects AS P
							    WHERE M.brands_id = B.id
								AND   M.type_equipments_id = T.id
								AND   E.models_id = M.id
								AND   L.projects_id = P.id
								AND   E.locations_id = L.id
								AND   P.users_id = U.id
								".$completeString."
								ORDER BY E.id DESC
								";
		$statement = $connection -> prepare($sql);
		$statement -> execute();
		$results   = $statement -> fetchAll();
		print_r($results);
		exit;
		if(!empty($results)){
			$i=0;
			
			foreach ($results as $row) {
				$array[$i] = array($row['name'], intval($row['quantity']));
				$i++;
			}
			$reply['interventions'] = $array;
			$reply['state']			= true;
		}
	} 
}
