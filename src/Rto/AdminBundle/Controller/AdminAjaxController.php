<?php

namespace Rto\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

//APIs
use Rto\CoreBundle\APIs\Core;
use Rto\CoreBundle\APIs\UsersFunctions;
use Rto\CoreBundle\APIs\TypeEquipmentsFunctions;
use Rto\CoreBundle\APIs\BrandsFunctions;
use Rto\CoreBundle\APIs\ModelsFunctions;
use Rto\CoreBundle\APIs\ProjectsFunctions;
use Rto\CoreBundle\APIs\TypeLocationsFunctions;
use Rto\CoreBundle\APIs\LocationsFunctions;

class AdminAjaxController extends Controller
{
	public function send($array){
		$reply = new Response(json_encode($array));
		return $reply;
	}
	public function createTypeEquipmentsAction()
	{
		$request 	  = $this -> getRequest() -> request;
		$data['name'] = $request -> get('name');
		
		$do = TypeEquipmentsFunctions::add($data, $this);
		if($do['state']){
			$type = 10;
		}else{
			$type = 9;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function updateTypeEquipmentsAction()
	{
		$request 	  = $this -> getRequest() -> request;
		$data['name'] = $request -> get('name');
		$data['id']   = $request -> get('id');
		
		$do = TypeEquipmentsFunctions::update($data, $this);
		if($do['state']){
			$type = 12;
		}else{
			$type = 11;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteTypeEquipmentsAction()
	{
		$request    = $this -> getRequest() -> request;
		$data['id'] = $request -> get('id');
		
		$do = TypeEquipmentsFunctions::delete($data,$this);
		if($do['state']){
			$type = 14;
		}else{
			$type = 13;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function createBrandAction()
	{
		$request      = $this -> getRequest() -> request;
		$data['name'] = $request -> get('name');
		
		$do = BrandsFunctions::add($data,$this);
		if($do['state']){
			$type = 16;
		}else{
			$type = 15;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function updateBrandAction()
	{
		$request      = $this -> getRequest() -> request;
		$data['id']   = $request -> get('id');
		$data['name'] = $request -> get('name');
		
		$do = BrandsFunctions::update($data, $this);
		if($do['state']){
			$type =18;
		}else{
			$type =17;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteBrandAction()
	{
		$request      = $this -> getRequest() -> request;
		$data['id']   = $request -> get('id');
		
		$do = BrandsFunctions::delete($data, $this);
		if($do['state']){
			$type =20;
		}else{
			$type =19;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function createModelAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['type'] 		 = $request -> get('type');
		$data['brand'] 		 = $request -> get('brand');
		$data['name'] 		 = $request -> get('name');
		$data['description'] = $request -> get('description');
		
		$do = ModelsFunctions::add($data, $this);
		if($do['state']){
			$type =22;
		}else{
			$type =21;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function updateModelAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		$data['type'] 		 = $request -> get('type');
		$data['brand'] 		 = $request -> get('brand');
		$data['name'] 		 = $request -> get('name');
		$data['description'] = $request -> get('description');

		$do = ModelsFunctions::update($data, $this);
		if($do['state']){
			$type =24;
		}else{
			$type =23;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteModelAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		
		$do = ModelsFunctions::delete($data, $this);
		if($do['state']){
			$type =26;
		}else{
			$type =25;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function createProjectAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['name'] 		 = $request -> get('name');
		$data['user'] 		 = $request -> get('responsable');
		
		$do = ProjectsFunctions::add($data, $this);
		if($do['state']){
			$type =28;
		}else{
			$type =27;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function updateProjectAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		$data['name'] 		 = $request -> get('name');
		$data['user'] 		 = $request -> get('responsable');
		
		$do = ProjectsFunctions::update($data, $this);
		if($do['state']){
			$type =30;
		}else{
			$type =29;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteProjectAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		
		$do = ProjectsFunctions::delete($data, $this);
		if($do['state']){
			$type =32;
		}else{
			$type =31;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function searchProjectAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('coordinator');
		
		$get = ProjectsFunctions::getProjectsByCoordinator($data, $this);
		if($get['state']){
			$type 	  = '';
			$projects = $get['data'];
		}else{
			$type     = 85;
			$projects = '';
		}
		$array = array('state'=>true, 'type'=>$type, 'projects'=>$projects);
		return $this -> send($array);
	}
	public function createTypeLocationsAction()
	{
		$request 	   = $this -> getRequest() -> request;
		$data['name']  = $request -> get('name');
		
		$do = TypeLocationsFunctions::add($data, $this);
		if($do['state']){
			$type = 34;
		}else{
			$type = 33;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function updateTypeLocationsAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		$data['name'] 		 = $request -> get('name');
		
		$do = TypeLocationsFunctions::update($data, $this);
		if($do['state']){
			$type =36;
		}else{
			$type =35;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteTypeLocationsAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		
		$do = TypeLocationsFunctions::delete($data, $this);
		if($do['state']){
			$type =38;
		}else{
			$type =37;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function createLocationAction()
	{
		$request           = $this -> getRequest() -> request;
		$data['type']      = $request -> get('type');
		$data['project']   = $request -> get('project');
		$data['name'] 	   = $request -> get('name');
		$data['latitude']  = $request -> get('latitude');
		$data['length']    = $request -> get('length');
		$data['gather']    = $request -> get('gather');
		$data['workplace'] = $request -> get('workplace');
		
		if($data['gather']){
			$data['gather'] = 1;
		}else{
			$data['gather'] = 0;	
		}
		
		if($data['workplace']){
			$data['workplace'] = 1;
		}else{
			$data['workplace'] = 0;	
		}
		
		$do = LocationsFunctions::add($data,$this);
		if($do['state']){
			$type = 40;
		}else{
			$type = 39;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function updateLocationAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		$data['name'] 		 = $request -> get('name');
		$data['project'] 	 = $request -> get('project');
		$data['type'] 		 = $request -> get('type');
		$data['latitude'] 	 = $request -> get('latitude');
		$data['length'] 	 = $request -> get('length');
		$data['gather']      = $request -> get('gather');
		$data['workplace']   = $request -> get('workplace');
		
		if($data['gather']){
			$data['gather'] = 1;
		}else{
			$data['gather'] = 0;	
		}
		
		if($data['workplace']){
			$data['workplace'] = 1;
		}else{
			$data['workplace'] = 0;	
		}
		
		$do = LocationsFunctions::update($data, $this);
		if($do['state']){
			$type =42;
		}else{
			$type =41;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteLocationAction()
	{
		$request 			 = $this -> getRequest() -> request;
		$data['id'] 		 = $request -> get('id');
		
		$do = LocationsFunctions::delete($data, $this);
		if($do['state']){
			$type =44;
		}else{
			$type =43;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
}
