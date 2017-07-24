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
use Rto\CoreBundle\Entity\Users;
use Rto\CoreBundle\Entity\Worktime;

class UsersFunctions 
{
	public static function add($data, $class)
	{
		$reply['state'] = false;
		$em				= $class -> getDoctrine() -> getManager();
		
		$user = new Users();
		$user -> setName($data['name']);
		$user -> setLastname($data['lastname']);
		$user -> setUserid($data['userid']);
		$user -> setEmail($data['email']);
		$user -> setPassword(Core::encode($data['password']));
		$user -> setRole($data['role']);
		$user -> setActive($data['state']);
		
		$em -> persist($user);
		$em -> flush();
		
		$worktime = new Worktime();
		$worktime -> setLocations(null);
		$worktime -> setStartDate(date_create(date('Y-m-d H:i:s')));
		$worktime -> setUsers($user);
		$worktime -> setEndDate(null);
		
		$em -> persist($worktime);
		$em -> flush();
		
		$log['title']       = 'New user '.$data['name'].' '.$data['lastname'].' has been added';
		$log['description'] = 'User created by ';
		$log['link']        = $class -> generateUrl('User_users');
				
		$do = LogsFunctions::add($log, $class);
		
		$data['title'] = 'Welcome! You have been added as user..';
		$data['from']  = 'no-reply@rtohall.com';
		$data['email'] = $data['email'];
		$data['view']  = 'RtoCoreBundle:mails:newUser.html.twig';
		
		$do = Core::sendMessage($data, $class);
		
		$reply['state'] = true;
		return $reply;
	}
	public static function users($class)
	{
		$reply['state'] = false;
		$users			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Users')
								 -> findAll();
		if(!empty($users)){
			$i = 0;
			foreach($users as $user){
				$data[$i]['edit'] = $class -> generateUrl('User_editUser', array('id'=>$user -> getId()));
				$data[$i]['user'] = $user;
				$i++;
			}
			$reply['state'] = true;
			$reply['data']  = $data;
		}
		return $reply;
	}
	public static function searchByEmail($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		if(is_null($data['id'])){
			$user 			= $class -> getDoctrine() 
								 	 -> getRepository('RtoCoreBundle:Users')
								 	 -> findOneByemail($data['email']);
		}else{
			$user			= $em -> createQueryBuilder();
			$user	 		-> select('u')
				   			-> from('RtoCoreBundle:Users', 'u')
				   			-> where('u.id != :id')
							-> andWhere('u.email = :email')
							-> setParameter('id', $data['id'])
							-> setParameter('email', $data['email']);
			$user 			= $user -> getQuery() 
									-> getResult(); 
		}
		if(empty($user)){
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function searchByUserId($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		if(is_null($data['id'])){
			$user 			= $class -> getDoctrine() 
								 	 -> getRepository('RtoCoreBundle:Users')
								 	 -> findOneByuserid($data['userid']);
		}else{
			$user			= $em -> createQueryBuilder();
			$user	 		-> select('u')
				   			-> from('RtoCoreBundle:Users', 'u')
				   			-> where('u.id != :id')
							-> andWhere('u.userid = :userid')
							-> setParameter('id', $data['id'])
							-> setParameter('userid', $data['userid']);
			$user 			= $user -> getQuery() 
									-> getResult(); 
		}
		if(empty($user)){
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function getUser($data, $class)
	{
		$reply['state'] = false;
		$user 			= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Users')
								 -> findOneByid($data['id']);
		if(!empty($user)){
			$reply['state'] = true;
			$reply['user']  = $user;
		}
		return $reply;
	}
	public static function update($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = UsersFunctions::getUser($data, $class);
		if($get['state']){
			$user = $get['user'];
			$user -> setName($data['name']);
			$user -> setLastname($data['lastname']);
			$user -> setUserid($data['userid']);
			$user -> setEmail($data['email']);
			$user -> setRole($data['role']);
			$user -> setActive($data['active']);
			
			$em -> persist($user);
			$em -> flush();
			
			$log['title']       = 'User '.$data['name'].' '.$data['lastname'].' has been updated';
			$log['description'] = 'User updated by ';
			$log['link']        = $class -> generateUrl('User_users');
					
			$do = LogsFunctions::add($log, $class);
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function delete($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getmanager();
		
		$get = UsersFunctions::getUser($data, $class);
		if($get['state']){
			$user = $get['user'];
			
			$log['title']       = 'User '.$user -> getName().' '.$user -> getLastname().' has been deleted';
			$log['description'] = 'User deleted by ';
			$log['link']        = $class -> generateUrl('User_users');
					
			$do = LogsFunctions::add($log, $class);
			
			$em   -> remove($user);
			$em   -> flush();
			
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function avaiableStaff($class)
	{
		$reply['state'] = false;
		$avaiables		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Worktime')
								 -> findBy(array('locations'=>NULL, 'endDate'=> NULL));
		if(!empty($avaiables)){
			$i=0;
			foreach ($avaiables as $avaiable) {
				$date1 = strtotime($avaiable -> getStartDate() -> format("Y-m-d H:i:s"));
				$date2 = strtotime(date("Y-m-d H:i:s")); 
				$data[$i]['timeWorked'] = ($date2-$date1)/ 86400;
				$data[$i]['avaiable']   = $avaiable;
				$i++; 
 			}
			$reply['data']  = $data;
			$reply['state']	= true;
		}
		return $reply;
	} 
	public static function assignedStaff($class)
	{
		$reply['state'] = false;
		$locations      = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Locations')
								 -> findByworkplace(1);
		if(!empty($locations)){
			$i = 0;
			foreach ($locations as $location) {
				$assigned = $class -> getDoctrine()
								   -> getRepository('RtoCoreBundle:Worktime')
								   -> findBy(array('locations'=>$location -> getId(), 'endDate'=> NULL));
				if(!empty($assigned)){
					$j = 0; $operators = '';
					foreach ($assigned as $operator) {
						$date1 		   = strtotime($operator -> getStartDate() -> format("Y-m-d H:i:s"));
						$date2 		   = strtotime(date("Y-m-d H:i:s"));
						$user['id']    = $operator -> getUsers() -> getId(); 
						$get		   = WorktimeFunctions::getTimeWorking($user, $class);
						if($get['state']){
							$time = $get['days'];
						}
						$operators[$j] = array('operator'=> $operator, 'timeWorked'=> $time);
						$j++;
					}
					$assigned = $operators;
					$data[$i] = array('location' => $location, 'assigned' => $operators);
				}else{
					$data[$i] = array('location' => $location, 'assigned' => '');
				}
				$i++;
			}
			$reply['assigned'] = $data;
			$reply['state']    = true;
		}
		return $reply; 
	}
	public static function releaseOperator($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		$worktime 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Worktime')
								 -> findOneByid($data['worktime']);
		if(!empty($worktime)){
			$date1 = strtotime($worktime -> getStartDate() -> format("Y-m-d H:i:s"));
			$date2 = strtotime(date("Y-m-d H:i:s"));
			if($date2 - $date1 < 86400){
				$em -> remove($worktime);
				$em -> flush();
			}else{
				$worktime -> setEndDate(date_create(date('Y-m-d H:i:s')));
				$em       -> persist($worktime);
				$em 	  -> flush();
			}
			
			$user['id'] = $data['pk'];
			$get = UsersFunctions::getUser($user, $class);
			if($get['state']){
				$userO = $get['user'];
			}else{
				$userO = '';
			}
			$worktime = new Worktime();
			$worktime -> setStartDate(date_create(date("Y-m-d H:i:s")));
			$worktime -> setLocations(NULL);
			$worktime -> setUsers($userO);
			$worktime -> setEndDate(NULL);
			
			$em -> persist($worktime);
			$em -> flush();
			
			$data           = array('worktime'=>$worktime-> getId(), 'pk'=> $userO -> getId());
			$reply['data']  = $data;
			$reply['state'] = true;
		}
		return $reply;
	}
	public static function moveOperator($data, $class)
	{
		$reply['state'] = false;
		$em 			= $class -> getDoctrine() -> getManager();
		$worktime 		= $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Worktime')
								 -> findOneByid($data['worktime']);
		
		if(!empty($worktime)){
			$date1 = strtotime($worktime -> getStartDate() -> format("Y-m-d H:i:s"));
			$date2 = strtotime(date("Y-m-d H:i:s"));
			if($date2 - $date1 < 86400){
				$em -> remove($worktime);
				$em -> flush();
			}else{
				$worktime -> setEndDate(date_create(date('Y-m-d H:i:s')));
				$em       -> persist($worktime);
				$em 	  -> flush();
			}
		}
		
		$user['id'] = $data['pk'];
 		$get = UsersFunctions::getUser($user, $class);
		if($get['state']){
			$userO = $get['user'];
		}else{
			$userO = "";
		}
		
		$location['id'] = $data['location'];
		$get = LocationsFunctions::getLocation($location, $class);
		if($get['state']){
			$locationO = $get['location'];
		}else{
			$locationO = "";
		}
		
		$newWorktime =  new Worktime();
		$newWorktime -> setUsers($userO);
		$newWorktime -> setLocations($locationO);
		$newWorktime -> setStartDate(date_create(date("Y-m-d H:i:s")));
		$newWorktime -> setEndDate(NULL);
		
		$em 		 -> persist($newWorktime);
		$em          -> flush();
		
		$data 			= "";
		$data 			= array('worktime'=> $newWorktime -> getId(), 'location'=> $newWorktime -> getLocations() -> getId(), 'pk'=> $newWorktime -> getUsers() -> getId());
		$reply['data']  = $data;
		$reply['state'] = true;
		
		return $reply;
	}
	public static function loadDetails($data, $class)
	{
		$reply['state'] = false;
		$worktimes       = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Worktime')
								 -> findBy(array('locations'=>$data['id'], 'endDate'=> NULL));
		if(!empty($worktimes)){
			$i    = 0;
			$data = '';
			foreach ($worktimes as $worktime) {
				if(file_exists('../web/images/users/'.md5($worktime -> getUsers() -> getId()).'.jpg')){
					$path = 'images/users/'.md5($worktime -> getUsers() -> getId()).'.jpg';
				}else{
					$path = 'images/users/'.md5(0).'.jpg';
				}
				$data[$i] = array('id'=> $worktime -> getUsers() -> getId(), 'userId' => $worktime -> getUsers() -> getUserid(), 'picture'=> $path, 'name'=>$worktime -> getUsers() -> getName(), 'lastName' => $worktime -> getUsers() -> getLastname() );
				$i++;
			}
			$reply['data']  = $data;
			$reply['state'] = true;
		}					
		return $reply;			 
	}
	public static function getCoordinators($class)
	{
		$reply['state'] = false;
		$coords		    = $class -> getDoctrine()
								 -> getRepository('RtoCoreBundle:Users')
								 -> findByrole('ROLE_COORD');
		if(!empty($coords)){
			$reply['coords'] = $coords;
			$reply['state']    = true;
		}
		return $reply;
	}
}
