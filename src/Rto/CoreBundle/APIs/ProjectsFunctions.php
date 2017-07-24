<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

//APIs
use Rto\CoreBundle\APIs\LogsFunctions;
use Rto\CoreBundle\APIs\Core;

//Entity
use Rto\CoreBundle\Entity\Projects;

class ProjectsFunctions
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$user['id']     = $data['user'];
		$get            = UsersFunctions::getUser($user, $class);
		$user			= $email = null;
		if($get['state']){
			$user  = $get['user'];
			$email = $user -> getEmail();
		}
		
		$project = new Projects();
		$project -> setName($data['name']);
		$project -> setUsers($user);

		$em -> persist($project);
		$em -> flush();
		
		$log['title']       = 'New project '.$data['name'].' has been added';
		$log['description'] = 'Project created by ';
		$log['link']        = '';
				
		$do = LogsFunctions::add($log, $class);
		
		if($email){
			$data['title'] = 'New Project for you.';
			$data['from']  = 'no-reply@rtohall.com';
			$data['email'] = $email;
			$data['view']  = 'RtoCoreBundle:mails:newProject.html.twig';
			
			$do = Core::sendMessage($data, $class);
		}
		
		$reply['state'] = true;
		return $reply;
	}
	public static function projects($class)
	{
		$reply['state'] = false;
		$projects		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Projects')
								 -> findAll();
		if(!empty($projects)){
			$i = 0;
			foreach($projects as $project){
				$data[$i]['edit']    = $class -> generateUrl('Admin_editProject', array('id'=>$project -> getId()));
				$data[$i]['view']    = $class -> generateUrl('Projects_view', array('id'=>$project -> getId()));
				$data[$i]['project'] = $project;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function getProject($data, $class)
	{
		$reply['state'] = false;
		$project 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Projects')
								 -> findOneByid($data['id']);
		if(!empty($project)){
			$reply['state']    = true;
			$reply['project']  = $project;
		}
		return $reply;
	}
	public static function update($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = ProjectsFunctions::getProject($data, $class);
		if($get['state']){
			$project = $get['project'];
			$email   = '';
			if($project -> getUsers()){
				$oldUser = $project -> getUsers() -> getId();
			}else{
				$oldUser = null;
			}
			 
			if($project -> getUsers() && $project -> getUsers() -> getId() != $data['user']){
				$email    = $project -> getUsers() -> getEmail();
				$data['title'] = 'Project Retired.';
				$data['from']  = 'no-reply@rtohall.com';
				$data['email'] = $email;
				$data['view']  = 'RtoCoreBundle:mails:retireProject.html.twig';
				$do 		   = Core::sendMessage($data, $class);
			}
			
			$user['id'] = $data['user'];
			$get 		= UsersFunctions::getUser($user, $class);
			$user       = $email = null;
			if($get['state']){
				$user  = $get['user'];
				$email = $user -> getEmail();
			}
			$project -> setName($data['name']);
			$project -> setUsers($user);
			
			$em -> persist($project);
			$em -> flush();
			
			$log['title']       = 'Project '.$data['name'].' has been updated';
			$log['description'] = 'Project updated by ';
			$log['link']        = '';
					
			$do = LogsFunctions::add($log, $class);
			
			if($email && $oldUser != $data['user'] ){
				$data['title'] = 'New Project for you.';
				$data['from']  = 'no-reply@rtohall.com';
				$data['email'] = $email;
				$data['view']  = 'RtoCoreBundle:mails:newProject.html.twig';
				$do 		   = Core::sendMessage($data, $class);
			}
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function delete($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = ProjectsFunctions::getProject($data, $class);
		if($get['state']){
			$project = $get['project'];
			
			$log['title']       = 'Project '.$project -> getName().' has been deleted';
			$log['description'] = 'Project deleted by ';
			$log['link']        = '';
					
			$do = LogsFunctions::add($log, $class);
			
			$em   -> remove($project);
			$em   -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getMyProjects($class)
	{
		$reply['state'] = false;
		$get 			= Core::getUser($class);
		if($get['state']){
			$userO = $get['user'];
		}
		$projects 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Projects')
								 -> findByusers($userO -> getId());
		if(!empty($projects)){
			$reply['state'] = true;
			$reply['projects']  = $projects;
		}
		return $reply;
	} 
	public static function getProjectsByCoordinator($data, $class)
	{
		$reply['state'] = false;
		$projects 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Projects')
								 -> findByusers($data['id']);
		if(!empty($projects)){
			$i    = 0;
			$data = '';
			foreach ($projects as $project) {
					$data[$i] = array('id'=>$project -> getId(), 'name'=>$project -> getName());
					$i++;
			}
			$reply['data']  = $data;
			$reply['state'] = true;
		}
		return $reply;
	} 
}
