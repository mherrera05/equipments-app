<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\LogsFunctions;

//Entity
use Rto\CoreBundle\Entity\Brands;

class BrandsFunctions 
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$brand = new Brands();
		$brand -> setName($data['name']);

		$em -> persist($brand);
		$em -> flush();
		
		$log['title']       = 'New brand '.$data['name'].' has been added';
		$log['description'] = 'Brand created by ';
		$log['link']        = '';
		
		$do = LogsFunctions::add($log, $class);
		
		$reply['state'] = true;
		return $reply;
	}
	public static function brands($class)
	{
		$reply['state'] = false;
		$brands			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Brands')
								 -> findAll();
		if(!empty($brands)){
			$i = 0;
			foreach($brands as $brand){
				$data[$i]['edit'] = $class -> generateUrl('Admin_editBrand', array('id'=>$brand -> getId()));
				$data[$i]['brand'] = $brand;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function getBrand($data, $class)
	{
		$reply['state'] = false;
		$brand 			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Brands')
								 -> findOneByid($data['id']);
		if(!empty($brand)){
			$reply['state'] = true;
			$reply['brand']  = $brand;
		}
		return $reply;
	}
	public static function update($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = BrandsFunctions::getBrand($data, $class);
		if($get['state']){
			$brand = $get['brand'];
			$brand -> setName($data['name']);
			
			$em -> persist($brand);
			$em -> flush();
			
			$log['title']       = 'Brand '.$data['name'].' has been updated';
			$log['description'] = 'Brand updated by ';
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
		
		$get = BrandsFunctions::getBrand($data, $class);
		if($get['state']){
			$brand = $get['brand'];
			
			$log['title']       = 'Brand '.$brand -> getName().' has been deleted';
			$log['description'] = 'Brand deleted by ';
			$log['link']        = '';
			
			$do = LogsFunctions::add($log, $class);
			
			$em   -> remove($brand);
			$em   -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
}
