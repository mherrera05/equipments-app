<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\LogsFunctions;

//Entity
use Rto\CoreBundle\Entity\TypeLocations;

class TypeLocationsFunctions
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$type = new TypeLocations();
		$type -> setName($data['name']);

		$em -> persist($type);
		$em -> flush();
		
		$log['title']       = 'New type of location '.$data['name'].' has been added';
		$log['description'] = 'Type of location created by ';
		$log['link']        = '';
				
		$do = LogsFunctions::add($log, $class);
		
		$reply['state'] = true;
		return $reply;
	}
	public static function typeLocations($class)
	{
		$reply['state'] = false;
		$types 			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:TypeLocations')
								 -> findAll();
		if(!empty($types)){
			$i = 0;
			foreach($types as $type){
				$data[$i]['edit'] = $class -> generateUrl('Admin_editTypeLocations', array('id'=>$type -> getId()));
				$data[$i]['type'] = $type;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function getTypeLocation($data, $class)
	{
		$reply['state'] = false;
		$type    		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:TypeLocations')
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
		
		$get = TypeLocationsFunctions::getTypeLocation($data, $class);
		if($get['state']){
			$type = $get['type'];
			$type -> setName($data['name']);
			
			$em -> persist($type);
			$em -> flush();
			
			$log['title']       = 'Type of location '.$data['name'].' has been updated';
			$log['description'] = 'Type of location updated by ';
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
		
		$get = TypeLocationsFunctions::getTypeLocation($data, $class);
		if($get['state']){
			$type = $get['type'];
			
			$log['title']       = 'Type of location '.$type -> getName().' has been deleted';
			$log['description'] = 'Type of location deleted by ';
			$log['link']        = '';
					
			$do = LogsFunctions::add($log, $class);
			
			$em   -> remove($type);
			$em   -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
}
