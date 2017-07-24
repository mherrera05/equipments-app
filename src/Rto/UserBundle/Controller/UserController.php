<?php

namespace Rto\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//APIs
use Rto\CoreBundle\APIs\UsersFunctions;
use Rto\CoreBundle\APIs\WorktimeFunctions;
use Rto\CoreBundle\APIs\LocationsFunctions;

class UserController extends Controller
{
	public function usersAction()
    {
    	$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		$session = array('id' => $user['id'], 'role'=> $user['role']);	
    	$url     = $this -> generateUrl('User_newUser');
		$get     = UsersFunctions::users($this);
		$users   = '';
		if($get['state']){
			$users = $get['data'];
		}
        return $this->render('RtoUserBundle:users:index.html.twig', array('url'=>$url, 'data'=> $users, 'session'=> $session));
    }
	public function newUserAction()
	{
		return $this -> render('RtoUserBundle:users:new.html.twig');	
	}
	public function editUserAction($id)
	{
		$data['id'] = $id;
		$user       = '';
		$get 		= UsersFunctions::getUser($data, $this);
		if($get['state']){
			$user = $get['user'];
		}
		return $this -> render('RtoUserBundle:users:edit.html.twig', array('data'=>$user));
	}
	public function assigningStaffAction()
	{
		$avaiables = $assigned = '';
		$get = UsersFunctions::avaiableStaff($this);
		if($get['state']){
			$avaiables = $get['data'];
		}
		$get = UsersFunctions::assignedStaff($this);
		if($get['state']){
			$assigned = $get['assigned'];
		}
		return $this -> render('RtoUserBundle:assigningStaff:index.html.twig', array('avaiables'=> $avaiables, 'assigned' => $assigned));	
	}
	public function recordAction()
	{
		$records = ''; 
		$session = $this -> getRequest() -> getSession();
		$user    = $session -> get('user');
		$get     = WorktimeFunctions::record($user, $this);
		if($get['state']){
			$records = $get['records'];
		}
		return $this -> render('RtoUserBundle:record:index.html.twig', array('data' => $records));
	}
	public function locateStaffAction()
	{
		$locations = '';
		$get = LocationsFunctions::locationWithStaff($this);
		if($get['state']){
			$locations = $get['data'];
		}
		return $this -> render('RtoUserBundle:locateStaff:index.html.twig', array('data'=>$locations));
	}
}
