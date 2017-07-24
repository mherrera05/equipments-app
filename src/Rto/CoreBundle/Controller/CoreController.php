<?php

namespace Rto\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Ps\PdfBundle\Annotation\Pdf;

//APIs
use Rto\CoreBundle\APIs\Core;
use Rto\CoreBundle\APIs\LogsFunctions;
use Rto\CoreBundle\APIs\WorktimeFunctions;
use Rto\CoreBundle\APIs\EquipmentsFunctions;
use Rto\CoreBundle\APIs\ProjectsFunctions;
use Rto\CoreBundle\APIs\ReportsFunctions;

use Symfony\Component\Security\Core\SecurityContext;

class CoreController extends Controller
{
	public function send($array){
		$reply = new Response(json_encode($array));
		return $reply;
	}
    public function logoAction()
    {
    	$url = $this -> generateUrl('Core_start');
        return $this -> render('RtoCoreBundle:logo:index.html.twig', array('url'=>$url));
    }
	public function menuAction()
	{
		
		/*$stringIp = $_SERVER['REMOTE_ADDR'];
		$intIp = ip2long($stringIp);
		print_r($stringIp);
		exit;*/
		Core::setUserToSession($this);
		$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		$role    = $user['role'];
		switch($role){
			case 'ROLE_ADMIN':{
				$menu = array(array('name'=>'Administration', 'url'=>'', 'list'=> array(array('name'=>'Type Equipments','url'=>$this->generateUrl('Admin_typeEquipments')),
																						array('name'=>'Brands','url'=>$this->generateUrl('Admin_brands')),
																						array('name'=>'Models','url'=>$this->generateUrl('Admin_models')),
																						array('name'=>'Projects','url'=>$this->generateUrl('Admin_projects')),
																						array('name'=>'Type Locations','url'=>$this->generateUrl('Admin_typeLocations')),
																						array('name'=>'Locations','url'=>$this->generateUrl('Admin_locations')))),
							  array('name'=>'Operators', 'url'=>'', 'list'=> array(array('name'=>'Staff','url'=>$this->generateUrl('User_users')))));
				break;
			}
			case 'ROLE_COORD':{
				$menu = array(array('name'=>'Administration', 'url'=>'', 'list'=> array(array('name'=>'Type Equipments','url'=>$this->generateUrl('Admin_typeEquipments')),
																						array('name'=>'Brands','url'=>$this->generateUrl('Admin_brands')),
																						array('name'=>'Models','url'=>$this->generateUrl('Admin_models')),
																						array('name'=>'Projects','url'=>$this->generateUrl('Admin_projects')),
																						array('name'=>'Type Locations','url'=>$this->generateUrl('Admin_typeLocations')),
																						array('name'=>'Locations','url'=>$this->generateUrl('Admin_locations')))),
							  array('name'=>'Operators', 'url'=>'', 'list'=> array(array('name'=>'Staff','url'=>$this->generateUrl('User_users')),
							  													   array('name'=>'Assigning Staff','url'=>$this->generateUrl('Users_assigningStaff')),
							  													   array('name'=>'Locate Staff','url'=>$this->generateUrl('User_locateStaff')),
							  													   array('name'=>'Record','url'=>$this->generateUrl('User_record')))),
							  array('name'=>'Equipments', 'url'=>'', 'list'=> array(array('name'=>'Equipments','url'=>$this->generateUrl('Equipments_start')),
							  													   array('name'=>'Installations','url'=>$this->generateUrl('Equipments_installations')),
							  													   array('name'=>'Uninstallations','url'=>$this->generateUrl('Equipments_uninstallations')),
							  													   array('name'=>'Locate Equipments','url'=>$this->generateUrl('Equipments_locateEquipments'))))/*,
							  array('name'=>'Reports', 'url'=>'', 'list'=> array(array('name'=>'General Report','url'=>$this->generateUrl('Reports_equipments'))))*/);
				break;
			}
			case 'ROLE_OPERATOR':{
				$menu = array(array('name'=>'Administration', 'url'=>'', 'list'=> array(array('name'=>'Locations','url'=>$this->generateUrl('Admin_locations')))),
							  array('name'=>'Operators', 'url'=>'', 'list'=> array(array('name'=>'Staff','url'=>$this->generateUrl('User_users')),
							  													   array('name'=>'Locate Staff','url'=>$this->generateUrl('User_locateStaff')),
							  													   array('name'=>'Record','url'=>$this->generateUrl('User_record')))),
							  array('name'=>'Equipments', 'url'=>'', 'list'=> array(array('name'=>'Equipments','url'=>$this->generateUrl('Equipments_start')),
							  													   array('name'=>'Installations','url'=>$this->generateUrl('Equipments_installations')),
							  													   array('name'=>'Uninstallations','url'=>$this->generateUrl('Equipments_uninstallations')),
							  													   array('name'=>'Locate Equipments','url'=>$this->generateUrl('Equipments_locateEquipments'))))/*,
							  array('name'=>'Reports', 'url'=>'', 'list'=> array(array('name'=>'General Report','url'=>$this->generateUrl('Reports_equipments'))))*/);
				break;
			}
		}
		return $this -> render('RtoCoreBundle:menu:index.html.twig', array('menu'=>$menu));
	}
	public function startAction()
	{
		$session = $this -> getRequest() -> getSession();
    	$get 	 = Core::getUser($this);
		$logs    = $participations = '';
		if($get['state'] == true){
			$get = LogsFunctions::logs($this);
			if($get['state']){
				$logs  = $get['data'];
				$start = $get['start'];
			}
			$get = ReportsFunctions::getParticipations($this);
			if($get['state']){
				$participations = $get['data'];
			}
			return $this->render('RtoCoreBundle:start:index.html.twig', array('data' => $logs,'start' => $start, 'participations'=>$participations));
		}
		else{
			$error = "";
        	return $this->render('RtoCoreBundle:login:index.html.twig',array('lastEmail' => $session->get(SecurityContext::LAST_USERNAME),
																				 'error' => $error));
		}
	}
	public function profileAction()
	{
		$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		$name    = $user['name'].' '.$user['lastname'];
		$url  = array(array('name'=>'Account', 'url'=>$this -> generateUrl('Profile_edit')),
					  array('name'=>'Log Out', 'url'=> $this -> generateUrl('logout')));
		return $this -> render('RtoCoreBundle:profile:index.html.twig', array('profile'=>$url, 'name'=>$name));
	}
	public function loginAction()
	{
		$request     = $this -> getRequest();
		$session     = $request -> getSession();
		
		// obtiene el error de inicio de sesión si lo hay
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} 
		else{
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
		}	
		return $this->render('RtoCoreBundle:login:index.html.twig', array('lastEmail' => $session->get(SecurityContext::LAST_USERNAME),
																			  'error' => $error));
	}
	public function testAction()
	{
		$data['title'] = 'Project Retired!...';
		$data['name']    = "PDVSA San Tome";
		return $this -> render('RtoCoreBundle:mails:retireProject.html.twig', array('data'=>$data));
	}
	public function widgetAction()
	{
		return $this -> render('RtoCoreBundle:start:widget.html.twig');
	} 
	public function widget2Action()
	{
		$uninstallations = $installations = $projects = '';
		$get 			 = EquipmentsFunctions::getCountUninstallations($this);
		if($get['state']){
			$uninstallations['count'] = $get['count'];
			$uninstallations['url']   = $this -> generateUrl('Equipments_uninstallations');
		}else{
			$uninstallations['count'] = 0;
			$uninstallations['url']   = '';
		}
		$get			 = EquipmentsFunctions::getCountInstallations($this);
		if($get['state']){
			$installations['count'] = $get['count'];
			$installations['url']   = $this -> generateUrl('Equipments_installations');
		}else{
			$installations['count'] = 0;
			$installations['url']   = '';
		} 
		$get = ProjectsFunctions::getMyProjects($this);
		if($get['state']){
			$projects = $get['projects'];
		}
		$get = ProjectsFunctions::projects($this);
		if($get['state']){
			$allProjects = $get['data'];
		}
		
		return $this -> render('RtoCoreBundle:start:widget2.html.twig', array('uninstallations'=>$uninstallations, 'installations'=>$installations, 'projects'=>$projects, 'allProjects'=>$allProjects));
	} 
	public function systemAction()
	{
		$data = array('name'=> 'APP', 'version'=> 'v 9.14', 'corporation'=>'Company Name, C.A.');
		return $this -> render('RtoCoreBundle:system:index.html.twig', array('data'=> $data));
	} 
	public function helpAction()
	{
		$data = array(array('title'=>'Put your content here :', 'description'=>'Put your content here to help users','list'=>''),
					  );
		return $this -> render('RtoCoreBundle:start:help.html.twig', array('data'=> $data));
	}
	public function boardAction()
	{
		$data = '';
		$get  = EquipmentsFunctions::getBoard($this);
		if($get['state']){
			$data = $get['data'];
		}
		return $this -> render('RtoCoreBundle:start:board.html.twig', array('data'=>$data));
	}
	public function logScrollAction()
	{
		$request 	   = $this -> getRequest() -> request;
		$data['start'] = $request -> get("start");
		$get  		= LogsFunctions::getLogsToScroll($data, $this);
		if($get['state']){
			$data  = $get['data'];
		}
		$array = array('state'=>true, 'data' => $data );
		return $this -> send($array);
	}
}
