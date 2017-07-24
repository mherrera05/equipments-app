<?php

namespace Rto\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//APIs
use Rto\CoreBundle\APIs\UsersFunctions;
use Rto\CoreBundle\APIs\TypeEquipmentsFunctions;
use Rto\CoreBundle\APIs\BrandsFunctions;
use Rto\CoreBundle\APIs\ModelsFunctions;
use Rto\CoreBundle\APIs\ProjectsFunctions;
use Rto\CoreBundle\APIs\TypeLocationsFunctions;
use Rto\CoreBundle\APIs\LocationsFunctions;

class AdminController extends Controller
{
	public function countTypeEquipmentsAction(){
		$get  = TypeEquipmentsFunctions::typeEquipments($this);
		$data = '';
		if($get['state']){
			$type  = $get['data'];
			$count = count($type);
		}
		return $this -> render('RtoAdminBundle:typeEquipments:widget.html.twig', array('count'=>$count,'data'=>$type));
	}
	public function typeEquipmentsAction()
	{
		$typeEquipments = '';
		$url 		    = $this -> generateUrl('Admin_newTypeEquipments');
		$get 			= TypeEquipmentsFunctions::typeEquipments($this);
		if($get['state']){
			$typeEquipments = $get['data'];
		}
		return $this -> render('RtoAdminBundle:typeEquipments:index.html.twig', array('url'=>$url, 'data'=>$typeEquipments));
	}
	public function newTypeEquipmentsAction()
	{
		return $this -> render('RtoAdminBundle:typeEquipments:new.html.twig');
	}
	public function editTypeEquipmentsAction($id)
	{
		$data['id'] = $id;
		$get 		= TypeEquipmentsFunctions::getTypeEquipments($data,$this);
		if($get['state']){
			$type = $get['type'];
		}
		return $this -> render('RtoAdminBundle:typeEquipments:edit.html.twig', array('data'=>$type));
	}
	public function countBrandsAction()
	{
		$brands = $count = 0;
		$get = BrandsFunctions::brands($this);
		if($get['state']){
			$brands = $get['data'];
			$count  = count($brands);
		}
		return $this -> render('RtoAdminBundle:brands:widget.html.twig', array('count'=> $count, 'data'=>$brands));
	}
	public function brandsAction(){
		$url    = $this -> generateUrl('Admin_newBrand');
		$brands = '';
		$get 	= BrandsFunctions::brands($this);
		if($get['state']){
			$brands = $get['data'];
		}
		return $this -> render('RtoAdminBundle:brands:index.html.twig', array('data'=>$brands, 'url'=> $url));
	}
	public function newBrandAction()
	{
		return $this -> render('RtoAdminBundle:brands:new.html.twig');
	}
	public function editBrandAction($id)
	{
		$data['id'] = $id;
		$get = BrandsFunctions::getBrand($data,$this);
		if($get['state']){
			$brand = $get['brand'];
		}
		return $this -> render('RtoAdminBundle:brands:edit.html.twig', array('data'=>$brand));
	}
	public function countModelsAction()
	{
		$models = $count = 0;
		$get = ModelsFunctions::models($this);
		if($get['state']){
			$models = $get['data'];
			$count  = count($models);
		}
		return $this -> render('RtoAdminBundle:models:widget.html.twig', array('count'=> $count, 'data'=>$models));
	}
	public function modelsAction()
	{
		$url    = $this -> generateUrl('Admin_newModel');
		$models = '';
		$get    = ModelsFunctions::models($this);
		if($get['state']){
			$models = $get['data'];
		}
		return $this -> render('RtoAdminBundle:models:index.html.twig', array('data'=>$models, 'url'=>$url));
	}
	public function newModelAction()
	{
		$get = TypeEquipmentsFunctions::typeEquipments($this);
		if($get['state']){
			$types = $get['data'];
		}else{
			$types = '';
		}
		$get = BrandsFunctions::brands($this);
		if($get['state']){
			$brands = $get['data'];
		}else{
			$brands = '';
		}
		return $this -> render('RtoAdminBundle:models:new.html.twig', array('types'=>$types, 'brands'=> $brands));
	} 
	public function editModelAction($id)
	{
		$data['id'] = $id;
		$get 		= ModelsFunctions::getModel($data,$this);
		if($get['state']){
			$model = $get['model'];
			
			$get = TypeEquipmentsFunctions::typeEquipments($this);
			if($get['state']){
				$types = $get['data'];
			}
			$get = BrandsFunctions::brands($this);
			if($get['state']){
				$brands = $get['data'];
			}
		}
		return $this -> render('RtoAdminBundle:models:edit.html.twig', array('data'=> $model, 'types'=>$types, 'brands'=>$brands)); 
	}
	public function countProjectsAction()
	{
		$projects = $count = 0;
		$get = ProjectsFunctions::projects($this);
		if($get['state']){
			$projects = $get['data'];
			$count    = count($projects);
		}
		return $this -> render('RtoAdminBundle:projects:widget.html.twig', array('count'=> $count, 'data'=>$projects));
	}
	public function projectsAction()
	{
		$url 	  = $this -> generateUrl('Admin_newProject');
		$projects = '';
		$get 	  = ProjectsFunctions::projects($this);
		if($get['state']){
			$projects = $get['data'];
		}
		return $this -> render('RtoAdminBundle:projects:index.html.twig', array('url'=>$url, 'data'=>$projects));
	}
	public function newProjectAction()
	{
		$users = '';
		$get   = UsersFunctions::getCoordinators($this);
		if($get['state']){
			$users = $get['coords'];
		}
		return $this -> render('RtoAdminBundle:projects:new.html.twig', array('responsables'=>$users));
	}
	public function editProjectAction($id)
	{
		$data['id'] = $id;
		$project    = $users = '';
		$get 		= ProjectsFunctions::getProject($data,$this);
		if($get['state']){
			$project = $get['project'];
		} 
		$get   = UsersFunctions::getCoordinators($this);
		if($get['state']){
			$users = $get['coords'];
		}
		return $this -> render('RtoAdminBundle:projects:edit.html.twig', array('data'=> $project, 'responsables'=>$users));
	}
	public function viewProjectAction($id)
	{
		$data['id'] = $id;
		$project    = $locations = $url = '';
		$get 		= ProjectsFunctions::getProject($data,$this);
		if($get['state']){
			$project = $get['project'];
		} 
		$data['project'] = $project -> getId();
		$get 		= LocationsFunctions::getLocationsByProject($data, $this);
		if($get['state']){
			$locations = $get['data'];
		}
		return $this -> render('RtoAdminBundle:projects:view.html.twig', array('project'=> $project, 'locations'=>$locations));
	}
	public function typeLocationsAction()
	{
		$url		   = $this -> generateUrl('Admin_newTypeLocations');
		$typeLocations = '';
		$get 		   = TypeLocationsFunctions::typeLocations($this);
		if($get['state']){
			$typeLocations = $get['data'];
		}
		return $this -> render('RtoAdminBundle:typeLocations:index.html.twig', array('data'=>$typeLocations, 'url'=> $url));
	}
	public function newTypeLocationsAction()
	{
		return $this -> render('RtoAdminBundle:typeLocations:new.html.twig');
	}
	public function editTypeLocationsAction($id)
	{
		$data['id'] = $id;
		$type 		= '';
		$get 		= TypeLocationsFunctions::getTypeLocation($data, $this);
		if($get['state']){
			$type = $get['type'];
		}
		return $this -> render('RtoAdminBundle:typeLocations:edit.html.twig', array('data'=> $type));
	}
	public function countLocationsAction()
	{
		$location = $count = 0;
		$get = LocationsFunctions::locations($this);
		if($get['state']){
			$location  = $get['data'];
			$count     = count($location);
		}
		return $this -> render('RtoAdminBundle:locations:widget.html.twig', array('count'=> $count, 'data'=>$location));
	}
	public function locationsAction()
	{
		$url 	   = $this -> generateUrl('Admin_newLocation');
		$locations = '';
		$get	   = LocationsFunctions::locations($this);
		if($get['state']){
			$locations = $get['data'];
		}
		return $this -> render('RtoAdminBundle:locations:index.html.twig', array('url'=> $url, 'data'=>$locations));
	}
	public function newLocationAction()
	{
		$types = $projects = '';
		$get   = TypeLocationsFunctions::typeLocations($this);
		if($get['state']){
			$types = $get['data'];
		}

		$get = ProjectsFunctions::projects($this);
		if($get['state']){
			$projects = $get['data'];
		} 
		return $this -> render('RtoAdminBundle:locations:new.html.twig', array('types'=>$types, 'projects'=>$projects));
	}
	public function editLocationAction($id)
	{
		$data['id'] = $id;
		$types		= $projects = '';
		$get		= LocationsFunctions::getLocation($data,$this);
		if($get['state']){
			$location = $get['location'];
			
			$get = TypeLocationsFunctions::typeLocations($this);
			if($get['state']){
				$types = $get['data'];
			}
			
			$get = ProjectsFunctions::projects($this);
			if($get['state']){
				$projects = $get['data'];
			}
		}
		return $this -> render('RtoAdminBundle:locations:edit.html.twig', array('types'=>$types, 'projects'=>$projects, 'data'=> $location));
	}
}
