<?php

namespace Rto\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

//APIs
use Rto\CoreBundle\APIs\UsersFunctions;
use Rto\CoreBundle\APIs\Core;

class UserAjaxController extends Controller
{
	public function send($array){
		$reply = new Response(json_encode($array));
		return $reply;
	}
	public function createUserAction()
	{
		$request 		  = $this -> getRequest() -> request;
		$data['name'] 	  = $request -> get('name');
		$data['lastname'] = $request -> get('lastname');
		$data['userid']   = $request -> get('userid');
		$data['email']    = $request -> get('email');
		$data['password'] = $request -> get('password');
		$data['role']     = $request -> get('role');
		$data['state']    = $request -> get('state');
		
		$do = UsersFunctions::add($data, $this);
		if($do['state']){
			$type = 2;
		}else{
			$type = 1;
		}
		
		$array = array('type'=>$type, 'state'=> true);
		return $this -> send($array);
	}
	public function searchEmailAction()
	{
		$request 	   = $this -> getRequest() -> request;
		$data['email'] = $request -> get('email');
		$data['id']	   = $request -> get("id");
		
		$get = UsersFunctions::searchByEmail($data, $this);
		
		$array = array('state'=>$get['state']);
		return $this -> send($array);
	}
	public function searchUserIdAction()
	{
		$request 	    = $this -> getRequest() -> request;
		$data['userid'] = $request -> get('userid');
		$data['id']	    = $request -> get("id");
		
		$get = UsersFunctions::searchByUserId($data, $this);
		
		$array = array('state'=>$get['state']);
		return $this -> send($array);
	}
	public function updateUserAction()
	{
		$request 	      = $this -> getRequest() -> request;
		$data['id']   	  = $request -> get('id');
		$data['name'] 	  = $request -> get('name');
		$data['lastname'] = $request -> get('lastname');
		$data['userid']   = $request -> get('userid');
		$data['email']    = $request -> get('email');
		$data['role']     = $request -> get('role');
		$data['active']   = $request -> get('state');
		
		$do = UsersFunctions::update($data, $this);
		if($do['state']){
			$type = 6;
		}else{
			$type = 5;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	}
	public function deleteUserAction()
	{
		$request    = $this -> getRequest() -> request;
		$data['id'] = $request -> get("id");
		
		$do = UsersFunctions::delete($data, $this);
		if($do['state']){
			$type = 8;
		}else{
			$type = 7;
		}
		$array = array('state'=>true, 'type'=>$type);
		return $this -> send($array);
	} 
	public function releaseOperatorAction()
	{
		$request 		  = $this -> getRequest() -> request;
		$data['worktime'] = $request -> get("worktime");
		$data['pk']       = $request -> get("pk");
		
		$do   = UsersFunctions::releaseOperator($data, $this);
		$data = "";
		if($do['state']){
			$data = $do['data'];
			$type = 72 ;
		}else{
			$type = 71;
		}
		$array = array('state'=>true, 'type'=>$type, 'data'=> $data);
		return $this -> send($array);
	}
	public function moveOperatorAction()
	{
		$request 		  = $this -> getRequest();
		$data['worktime'] = $request -> get("worktime");
		$data['pk']		  = $request -> get("pk");
		$data['location'] = $request -> get("location");
		
		$do   = UsersFunctions::moveOperator($data, $this);
		$data = "";
		if($do['state']){
			$data = $do['data'];
			$type = 72;
		}else{
			$type = 71;
		}
		$array = array('state'=>true, 'type'=>72, 'data' => $data);
		return $this -> send($array);
	}
	public function loadDetailsAction()
	{
		$request 		  = $this -> getRequest();
		$data['id']       = $request -> get("id");
		
		$get = UsersFunctions::loadDetails($data, $this);
		if($get['state']){
			$type  = '';
			$users = $get['data'];
		}else{
			$type  = 61;
			$users = '';
		}
		
		$array = array('state'=>true, 'type'=>$type, 'users' => $users);
		return $this -> send($array);
	}
}
