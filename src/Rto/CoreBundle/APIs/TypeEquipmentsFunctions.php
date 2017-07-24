<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\LogsFunctions;

//Entity
use Rto\CoreBundle\Entity\TypeEquipments;

class TypeEquipmentsFunctions 
{
	public static function searchTypeEquipments($data, $class)
	{
		$reply['state'] = false;
		if(is_null($data['id'])){
			$type       = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:TypeEquipments')
								 -> findByname($data['name']);			
		}else{
			
		}
		if(!empty($type)){
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$type = new TypeEquipments();
		$type -> setName($data['name']);

		$em -> persist($type);
		$em -> flush();
		
		$log['title']       = 'New type of equipment '.$data['name'].' has been added';
		$log['description'] = 'Type of equipment created by ';
		$log['link']        = '';
				
		$do = LogsFunctions::add($log, $class);
		
		$reply['state'] = true;
		return $reply;
	}
	public static function typeEquipments($class)
	{
		$reply['state'] = false;
		$types			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:TypeEquipments')
								 -> findAll();
		if(!empty($types)){
			$i = 0;
			foreach($types as $type){
				$data[$i]['edit'] = $class -> generateUrl('Admin_editTypeEquipments', array('id'=>$type -> getId()));
				$data[$i]['type'] = $type;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function getTypeEquipments($data, $class)
	{
		$reply['state'] = false;
		$type 			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:TypeEquipments')
								 -> findOneByid($data['id']);
		if(!empty($type)){
			$reply['state'] = true;
			$reply['type']  = $type;
		}
		return $reply;
	}
	public static function update($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = TypeEquipmentsFunctions::getTypeEquipments($data, $class);
		if($get['state']){
			$type = $get['type'];
			$type -> setName($data['name']);
			
			$em -> persist($type);
			$em -> flush();
			
			$log['title']       = 'Type of equipment '.$data['name'].' has been updated';
			$log['description'] = 'Type of equipment updated by ';
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
		
		$get = TypeEquipmentsFunctions::getTypeEquipments($data, $class);
		if($get['state']){
			$type = $get['type'];
			
			$log['title']       = 'Type of equipment '.$type -> getName().' has been deleted';
			$log['description'] = 'Type of equipment deleted by ';
			$log['link']        = '';
					
			$do = LogsFunctions::add($log, $class);
			
			$em   -> remove($type);
			$em   -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
}
