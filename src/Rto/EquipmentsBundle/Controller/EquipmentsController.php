<?php

namespace Rto\EquipmentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//APIs
use Rto\CoreBundle\APIs\EquipmentsFunctions;
use Rto\CoreBundle\APIs\ModelsFunctions;
use Rto\CoreBundle\APIs\LocationsFunctions;
use Rto\CoreBundle\APIs\UsersFunctions;
use Rto\CoreBundle\APIs\TypeEquipmentsFunctions;
use Rto\CoreBundle\APIs\BrandsFunctions;
use Rto\CoreBundle\APIs\ProjectsFunctions;

class EquipmentsController extends Controller
{
    public function equipmentsAction()
    {
    	$url    = $this -> generateUrl('Equipments_newEquipment');
		$equips = '';
    	$get    = EquipmentsFunctions::equipments($this);
		if($get['state']){
			$equips = $get['data'];
		}
        return $this->render('RtoEquipmentsBundle:equipments:index.html.twig', array('data' => $equips, 'url'=>$url));
    }
	public function newEquipmentAction()
	{
		$types = $brands = $models = $locations = $users = '';
		
		$get = TypeEquipmentsFunctions::typeEquipments($this);
		if($get['state']){
			$types = $get['data'];
		}
		
		$get = BrandsFunctions::brands($this);
		if($get['state']){
			$brands = $get['data'];
		}
		
		$get = ModelsFunctions::models($this);
		if($get['state']){
			$models = $get['data'];
		}
		
		$get = LocationsFunctions::locations($this);
		if($get['state']){
			$locations = $get['data'];
		}
		
		$get = UsersFunctions::users($this);
		if($get['state']){
			$users = $get['data'];
		}
		
		$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		
		return $this -> render('RtoEquipmentsBundle:equipments:new.html.twig', array('types'=>$types,'brands'=>$brands, 'models'=>$models, 'locations'=>$locations, 'users'=>$users, 'session'=>$user));
	}
	public function editEquipmentAction($id)
	{
		$types = $brands = $models = $locations = $users = '';
		
		$equip['id'] = $id;
		$get 		 = EquipmentsFunctions::getEquipment($equip, $this);
		if($get['state']){
			$equip = $get['equip'];
		}
		
		$get = TypeEquipmentsFunctions::typeEquipments($this);
		if($get['state']){
			$types = $get['data'];
		}
		
		$get = BrandsFunctions::brands($this);
		if($get['state']){
			$brands = $get['data'];
		}
		
		$search['brand'] = $equip -> getModels() -> getBrands() -> getId();
		$search['type']  = $equip -> getModels() -> getTypeEquipments() -> getId();
		$get 			 = ModelsFunctions::searchModels($search, $this);
		if($get['state']){
			$models = $get['data'];
		}
		
		$get = LocationsFunctions::locations($this);
		if($get['state']){
			$locations = $get['data'];
		}
		
		$get = UsersFunctions::users($this);
		if($get['state']){
			$users = $get['data'];
		}
		
		$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		
		return $this -> render('RtoEquipmentsBundle:equipments:edit.html.twig', array('types'=>$types,'brands'=>$brands, 'models'=>$models, 'locations'=>$locations, 'users'=>$users, 'session'=>$user, 'data'=>$equip));
	}
	public function viewEquipmentAction($id)
	{
		$equipment  = '';
		$data['id'] = $id;
		$get 	    = EquipmentsFunctions::getEquipment($data, $this);
		if($get['state']){
			$equipment = $get['equip'];
		}
		$url		= $this -> generateUrl('Equipments_editEquipment', array('id'=>$equipment -> getId()));
		return $this -> render('RtoEquipmentsBundle:equipments:view.html.twig', array('data'=>$equipment, 'url'=>$url));
	}
	public function installationsAction()
	{
		$installations = '';
		$url		   = $this -> generateUrl('Equipments_installEquipments'); 
		$get 		   = EquipmentsFunctions::installations($this);
		if($get['state']){
			$installations = $get['data'];
		}
		return $this -> render('RtoEquipmentsBundle:installEquipments:index.html.twig', array('data'=>$installations, 'url'=> $url));
	}
	public function installEquipmentsAction()
	{
		$projects = $users = $user = $serials = '';
		$get = EquipmentsFunctions::equipments($this);
		if($get['state']){
			$serials = $get['data'];
		}
		
		$get = ProjectsFunctions::projects($this);
		if($get['state']){
			$projects = $get['data'];
		}
		
		$get = UsersFunctions::users($this);
		if($get['state']){
			$users = $get['data'];
		}
		
		$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		
		return $this -> render('RtoEquipmentsBundle:installEquipments:new.html.twig', array('projects'=>$projects, 'users'=>$users, 'session' => $user, 'serials' => $serials));
	}
	public function viewInstallationAction($id)
	{
		$installation = '';
		$equipments   = '';
		$data['id']   = $id;
		$get          = EquipmentsFunctions::getInstallation($data,$this);
		if($get['state']){
			$installation = $get['installation'];
			$equipments    = $get['equipments'];
		}
		
		return $this -> render('RtoEquipmentsBundle:installEquipments:view.html.twig', array('data'=>$installation, 'equipments'=>$equipments));
	}
	public function locateEquipmentsAction()
	{
		$locations = $types = '';
		$get 	   = LocationsFunctions::locationWithEquipments($this);
		if($get['state']){
			$locations = $get['data'];
		}
		$get 	   = TypeEquipmentsFunctions::typeEquipments($this);
		if($get['state']){
			$types = $get['data'];
		}
		return $this -> render('RtoEquipmentsBundle:installEquipments:locate.html.twig', array('data'=> $locations,'types'=> $types));
	}
	public function uninstallationsAction()
	{
		$uninstallations = '';
		$url			 = $this-> generateUrl('Equipments_uninstallEquipments');
		$get 			 = EquipmentsFunctions::uninstallations($this);
		if($get['state']){
			$uninstallations = $get['data'];
		}
		return $this -> render('RtoEquipmentsBundle:uninstallEquipments:index.html.twig', array('data'=> $uninstallations, 'url'=> $url));
	}
	public function uninstallEquipmentsAction()
	{
		$projects = $locations = $centers = $users = '';
		$get = ProjectsFunctions::projects($this);
		if($get['state']){
			$projects = $get['data'];
		}
		$get = LocationsFunctions::centers($this);
		if($get['state']){
			$centers = $get['locations'];
		}
		$get = UsersFunctions::users($this);
		if($get['state']){
			$users = $get['data'];
		}
		$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		return $this -> render('RtoEquipmentsBundle:uninstallEquipments:new.html.twig', array('projects' => $projects, 'centers'=>$centers, 'users' => $users, 'session' => $user));
	}
	public function viewUninstallationAction($id)
	{
		$data['id']     = $id;
		$uninstallation = '';
		$get			= EquipmentsFunctions::getUninstallation($data, $this);
		if($get['state']){
			$uninstallation  = $get['uninstallation'];
			$equipments      = $get['equipments'];
		}
		return $this -> render('RtoEquipmentsBundle:uninstallEquipments:view.html.twig', array('data'=>$uninstallation,'equipments'=>$equipments));
	}
	public function reportsAction()
	{
		$users = $projects = $locations = $types = $brands = $models = '';
		$get = UsersFunctions::users($this);
		if($get['state']){
			$users = $get['data'];
		}
		$get = ProjectsFunctions::projects($this);
		if($get['state']){
			$projects = $get['data'];
			foreach($projects as $project){
				$arrayProject[] = array('id'=>$project['project'] -> getId(), 'name'=>$project['project'] -> getName());
			}
		}
		$get = LocationsFunctions::locations($this);
		if($get['state']){
			$locations = $get['data'];
		}
		$get = BrandsFunctions::brands($this);
		if($get['state']){
			$brands = $get['data'];
		}
		$get = TypeEquipmentsFunctions::typeEquipments($this);
		if($get['state']){
			$types = $get['data'];
		}
		$get = ModelsFunctions::models($this);
		if($get['state']){
			$models = $get['data'];
		}
		return $this -> render('RtoEquipmentsBundle:reports:index.html.twig', array('users'=>$users,'projects'=>$projects,'locations'=>$locations,'types'=>$types,'brands'=>$brands,'models'=>$models, 'arrayProject'=>$arrayProject));
	}
}
