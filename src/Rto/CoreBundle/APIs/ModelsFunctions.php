<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\LogsFunctions;
use Rto\CoreBundle\APIs\TypeEquipmentsFunctions;
use Rto\CoreBundle\APIs\BrandsFunctions;

//Entity
use Rto\CoreBundle\Entity\Models;

class ModelsFunctions 
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$type['id'] = $data['type'];
		$get		= TypeEquipmentsFunctions::getTypeEquipments($type,$class);
		if($get['state']){
			$data['type'] = $get['type'];
		}else{
			return $reply;
		}
		
		$brand['id'] = $data['brand'];
		$get		 = BrandsFunctions::getBrand($brand, $class);
		if($get['state']){
			$data['brand'] = $get['brand'];
		}else{
			return $reply;
		}
		
		$model = new Models();
		$model -> setName($data['name']);
		$model -> setTypeEquipments($data['type']);
		$model -> setBrands($data['brand']);
		$model -> setDescription($data['description']);

		$em -> persist($model);
		$em -> flush();
		
		$log['title']       = 'New model '.$data['name'].' has been added';
		$log['description'] = 'Model created by ';
		$log['link']        = '';
		
		$do = LogsFunctions::add($log, $class);
		
		$reply['state'] = true;
		return $reply;
	}
	public static function models($class)
	{
		$reply['state'] = false;
		$models			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Models')
								 -> findAll();
		if(!empty($models)){
			$i = 0;
			foreach($models as $model){
				$data[$i]['edit'] = $class -> generateUrl('Admin_editModel', array('id'=>$model -> getId()));
				$data[$i]['model'] = $model;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function getModel($data, $class)
	{
		$reply['state'] = false;
		$model 			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Models')
								 -> findOneByid($data['id']);
		if(!empty($model)){
			$reply['state'] = true;
			$reply['model']  = $model;
		}
		return $reply;
	}
	public static function update($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = ModelsFunctions::getModel($data, $class);
		if($get['state']){
			$model = $get['model'];
			
			$type['id'] = $data['type'];
			$get 		= TypeEquipmentsFunctions::getTypeEquipments($type, $class);
			if($get['state']){
				$data['type'] = $get['type'];
			}
			
			$brand['id'] = $data['brand'];
			$get		 = BrandsFunctions::getBrand($brand,$class);
			if($get['state']){
				$data['brand'] = $get['brand'];
			}
			
			$model -> setName($data['name']);
			$model -> setTypeEquipments($data['type']);
			$model -> setBrands($data['brand']);
			$model -> setDescription($data['description']);
			
			$em -> persist($model);
			$em -> flush();
			
			$log['title']       = 'Model '.$data['name'].' has been updated';
			$log['description'] = 'Model updated by ';
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
		
		$get = ModelsFunctions::getModel($data, $class);
		if($get['state']){
			$model = $get['model'];
			
			$log['title']       = 'Model '.$model -> getName().' has been deleted';
			$log['description'] = 'Model deleted by ';
			$log['link']        = '';
			
			$do = LogsFunctions::add($log, $class);
			
			$em   -> remove($model);
			$em   -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function searchModels($data, $class)
	{
		$reply['state'] = false;
		$models			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Models')
								 -> findBy(array('brands'=>$data['brand'], 'typeEquipments'=>$data['type']));
		
		if(!empty($models)){
			$data  = '';
			$i 	   = 0;
			foreach ($models as $model) {
				$data[$i] = array('id'=>$model -> getId(), 'name'=>$model -> getName());
				$i++;
			}
			$reply['data']   = $data;
			$reply['models'] = $models;
			$reply['state']  = true;
		}
		return $reply;
	}
}
