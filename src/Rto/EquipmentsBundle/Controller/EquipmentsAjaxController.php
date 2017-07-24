<?php

namespace Rto\EquipmentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

//APIs
use Rto\CoreBundle\APIs\EquipmentsFunctions;
use Rto\CoreBundle\APIs\ModelsFunctions;
use Rto\CoreBundle\APIs\LocationsFunctions;
use Rto\CoreBundle\APIs\UsersFunctions;
use Rto\CoreBundle\APIs\TypeEquipmentsFunctions;
use Rto\CoreBundle\APIs\BrandsFunctions;

class EquipmentsAjaxController extends Controller
{
	public function send($array){
		$reply = new Response(json_encode($array));
		return $reply;
	}
	public function searchModelsAction()
	{
		$request 	   = $this -> getRequest() -> request;
		$data['type']  = $request -> get('type');
		$data['brand'] = $request -> get('brand');
		
		$models		   = '';
		$get 		   = ModelsFunctions::searchModels($data, $this);
		if($get['state']){
			$type = '';
			$models = $get['data'];
		}else{
			$type = 45;
		}
		$array = array('state'=>true, 'type'=>$type, 'models'=> $models);
		return $this -> send($array);
	}
	public function searchSerialAction()
	{
		$request 	    = $this -> getRequest() -> request;
		$data['id']     = $request -> get('id');
		$data['serial'] = $request -> get('serial');
		
		$get 		    = EquipmentsFunctions::searchSerial($data, $this);

		$array = array('state'=>$get['state']);
		return $this -> send($array);
	}
	public function searchMacAction()
	{
		$request 	    = $this -> getRequest() -> request;
		$data['id']     = $request -> get('id');
		$data['mac']    = $request -> get('mac');
		
		$get 		    = EquipmentsFunctions::searchMac($data, $this);

		$array = array('state'=>$get['state']);
		return $this -> send($array);
	}
	public function createEquipmentAction()
	{
		$request 	      = $this -> getRequest() -> request;
		$data['model']    = $request -> get('model');
		$data['serial']   = strtoupper($request -> get('serial'));
		$data['mac']      = strtoupper($request -> get('mac'));
		$data['hostname'] = strtoupper($request -> get('hostname'));
		$data['location'] = $request -> get('location');
		$data['user']     = $request -> get('responsable');
		$data['state']    = $request -> get('state');
		
		$do = EquipmentsFunctions::add($data, $this);
		if($do['state']){
			$type = 48;
		}else{
			$type = 47;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	} 
	public function updateEquipmentAction()
	{
		$request 	      = $this -> getRequest() -> request;
		$data['id']       = $request -> get('id');
		$data['model']    = $request -> get('model');
		$data['serial']   = strtoupper($request -> get('serial'));
		$data['mac']      = strtoupper($request -> get('mac'));
		$data['hostname'] = strtoupper($request -> get('hostname'));
		$data['location'] = $request -> get('location');
		$data['user']     = $request -> get('responsable');
		$data['state']    = $request -> get('state');
		
		$do = EquipmentsFunctions::update($data, $this);
		if($do['state']){
			$type = 50;
		}else{
			$type = 49;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	} 
	public function deleteEquipmentAction()
	{
		$request 	      = $this -> getRequest() -> request;
		$data['id']       = $request -> get('id');
		
		$do = EquipmentsFunctions::delete($data, $this);
		if($do['state']){
			$type = 52;
		}else{
			$type = 51;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function searchLocationAction()
	{
		$request 	      = $this -> getRequest() -> request;
		$data['project']  = $request -> get('project');
		
		$get = LocationsFunctions::getLocationsByProject($data, $this);
		if($get['state']){
			$type 	   = '';
			$locations = $get['data'];
		}else{
			$type      = 53;
			$locations = '';
		}
		$array = array('state'=>true, 'type'=>$type, 'locations'=>$locations);
		return $this -> send($array);
	}
	public function searchEquipmentAction()
	{
		$request 	      = $this -> getRequest() -> request;
		$data['serial']   = $request -> get('serial');
		
		$get = EquipmentsFunctions::getEquipmentBySerial($data, $this);
		if($get['state']){
			$equipmentO = $get['equip'];
			$relationO  = $get['relation'];
			if($equipmentO -> getState() == 1){
				if($equipmentO -> getLocations() -> getGather() == 1){
					if(!empty($relationO) && ($relationO -> getInstallation() -> getState() == 0)){
						$type      = 57;
						$equipment = '';
					}else{
						$type 	    = 56;
						$equipment  = $get['data'];
					}
				}else{
					$type      = 57;
					$equipment = '';
				}
			}else{
				$type      = 77;
				$equipment = '';
			}
		}else{
			$type      = 55;
			$equipment = '';
		}
		$array = array('state'=>true, 'type'=>$type, 'equipment'=>$equipment);
		return $this -> send($array);
	}
	public function createInstallEquipmentsAction()
	{
		$request 		     = $this -> getRequest() -> request;
		$data['date']        = $request -> get('date');
		$data['location']    = $request -> get('location');
		$data['user']   	 = $request -> get('responsable');
		$data['state']       = $request -> get('state');
		$data['comments']    = $request -> get('comments');
		$data['equipments']  = $request -> get('equipments');
		$data['copy']        = $request -> get('refer');
		$data['equipments']  = explode(',',$data['equipments']);
		
		$do = EquipmentsFunctions::addInstallation($data, $this);
		if($do['state']){
			$type = 60;
		}else{
			$type = 59;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteInstallationAction()
	{
		$request    = $this -> getRequest() -> request;
		$data['id'] = $request -> get("id");
		
		$do = EquipmentsFunctions::deleteInstallation($data, $this);
		if($do['state']){
			$type = 74;
		}else{
			$type = 73;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	} 
	public function loadDetailsAction()
	{
		$request 	  = $this -> getRequest() -> request;
		$data['id']   = $request -> get('id');
		
		$get = EquipmentsFunctions::loadDetails($data, $this);
		if($get['state']){
			$equipments = $get['data'];
			$type       = '';
		}else{
			$equipments = '';
			$type       = 61;
		}
		$array = array('state'=>true, 'type'=>$type, 'equipments'=> $equipments);
		return $this -> send($array);
	}
	public function createUninstallationAction()
	{
		$request			 = $this -> getRequest() -> request;
		$data['date'] 	     = $request -> get('date');
		$data['location'] 	 = $request -> get('location');
		$data['responsable'] = $request -> get('responsable');
		$data['state']		 = $request -> get('state');
		$data['center']		 = $request -> get('center');
		$data['comments']	 = $request -> get('comments');
		$data['equipments']	 = $request -> get('equipments');
		$data['type']		 = $request -> get("type");
		$data['refer']		 = $request -> get("refer");
		$data['equipments']  = explode(',', $data['equipments']);
		
		$do = EquipmentsFunctions::addUninstallation($data, $this);
		if($do['state']){
			$type = 66;
		}else{
			$type = 65;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteUninstallationAction()
	{
		$request 	= $this -> getRequest() -> request;
		$data['id'] = $request -> get("id");
		
		$do = EquipmentsFunctions::deleteUninstallation($data, $this);
		if($do['state']){
			$type = 76;
		}else{
			$type = 77;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function completeInstallationAction()
	{
		$request    = $this -> getRequest() -> request;
		$data['id'] = $request -> get('id');
		
		$do = EquipmentsFunctions::completeInstallation($data, $this);
		if($do['state']){
			$type = 68;
		}else{
			$type = 67;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function completeUninstallationAction()
	{
		$request = $this -> getRequest();
		$data['id'] = $request -> get('id');
		
		$do = EquipmentsFunctions::completeUninstallation($data, $this);
		if($do['state']){
			$type = 70;
		}else{
			$type = 69;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteInstallEquipmentsAction()
	{
		$request    = $this -> getRequest() -> request;
		$data['id'] = $request -> get("id");
		
		$do = EquipmentsFunctions::deleteInstallation($data, $this);
		if($do['state']){
			$type = 74;
		}else{
			$type = 73;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteUninstallEquipmentsAction()
	{
		$request    = $this -> getRequest() -> request;
		$data['id'] = $request -> get("id");
		
		$do = EquipmentsFunctions::deleteUninstallation($data, $this);
		if($do['state']){
			$type = 76;
		}else{
			$type = 75;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function searchLocationTypeEquipmentsAction()
	{
		$request    = $this -> getRequest() -> request;
		$data['id'] = $request -> get("type");
		$locations  = '';
		
		$get = LocationsFunctions::locationByTypeEquipments($data, $this);
		if($get['state']){
			$locations = $get['data'];
			$type = 82;
		}else{
			$type = 83;
		}
		$array = array('state'=>true, 'type'=>$type, 'data'=>$locations);
		return $this -> send($array);
	}
	public function getReportAction()
	{
		$request    		 = $this -> getRequest() -> request;
		$data['coordinator'] = $request -> get("coordinator");
		$data['project']     = $request -> get("project");
		$data['location']    = $request -> get("location");
		$data['brand']		 = $request -> get("brand");
		$data['type']        = $request -> get("type");
		$data['model']       = $request -> get("model");
		
		$get = EquipmentsFunctions::getReport($data, $this);
		if($get['state']){
			$report = $get['data'];
		}
		$array = array('state'=>true, 'type'=>$type, 'data'=>$data);
		return $this -> send($array);
	}
}
