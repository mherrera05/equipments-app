<?php
namespace Rto\CoreBundle\APIs;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

class Core 
{
	public static function getToken($class) 
	{
		$token = $class -> get('security.context') -> getToken();
		$reply['state'] = true;
		$reply['token'] = $token;
		return $reply;
	}
	public static function getUser($class) 
	{
		$get = Core::getToken($class);
		if ($get['state'] == true) {
			$token = $get['token'];
			$user = $token -> getUser();
			if ($user != 'anon.') {
				$reply['state'] = true;
				$reply['user'] = $user;
			} else {
				$reply['state'] = false;
			}
		}
		return $reply;
	}
	public static function setUserToSession($class)
	{
		$request = $class -> getRequest();
		$session = $request -> getSession();
		
		if(!$session->has("user"))
		{
			$get  	 = Core::getUser($class);
			$user 	 = $get['user'];
			$data 	 = array('id'=>$user -> getId(),'email'=>$user -> getEmail(), 'name'=>$user -> getName(), 'lastname'=> $user -> getLastname(), 'role'=>$user -> getRole(), 'userid'=> $user -> getUserid());
			$session -> set('user',$data);
			$session -> save();
		}
		return 0;
	}
	public static function encode($password)
	{
		return hash("sha512", $password);
	}
	public static function sendMessage($data, $class)
	{
		if(!array_key_exists('copy', $data)){
			$data['copy'] = null;
		}
		$transport = \Swift_SmtpTransport::newInstance();
		$mailer = \Swift_Mailer::newInstance($transport);
		$message = \Swift_Message::newInstance()
					   ->setSubject($data['title'])
					   ->setFrom('no-reply@rtohall.com')
					   ->setTo($data['email'])
					   ->setCc($data['copy'])
					   ->setBody($class->renderView($data['view'], array('data'=>$data)),'text/html');
			
		//$mailer->send($message);
		return 0;
	}
	public static function existsPicture($data)
	{
		$reply['state'] = false;
		if(file_exists('images/users/'.md5($data['id']).'.jpg')){
			$reply['url']   = 'images/users/'.md5($data['id']).'.jpg';
			$reply['state'] = true;
		}else{
			$reply['url'] = 'images/users/default.jpg';
		}
		return $reply;
	}
	public static function uploadPicture($data, $class){
		$target = 'images/users/';
		$user   = Core::getUser($class);
		$user   = $user['user'];
		
		move_uploaded_file($data['file']['tmp_name'], $target.md5($user -> getId()).".jpg");
		return true;
	}
	public static function deletePicture($class){
		$user       = Core::getUser($class);
		$user       = $user['user'];
		$data['id'] = $user -> getId();
		
		$get = Core::existsPicture($data);
		if($get['state']){
			unlink($get['url']);
		}  
		return true;
	}
	public static function movePicture($class){
		$user       = Core::getUser($class);
		$user       = $user['user'];
		$data['id'] = $user -> getId();
		
		$get = Core::existsPicture($data);
		if($get['state']){
			rename('../web/images/tmp/'.md5($data['id']).'.jpg','../web/images/users/'.md5($data['id']).'.jpg');
		}  
		return true;
	}
	public static function getUserImage($data, $class)
	{
		$reply['state'] = false;
		if(file_exists('images/users/'.md5($data['id']).'.jpg')){
			$reply['url']   = 'images/users/'.md5($data['id']).'.jpg';
			$reply['state'] = true;
		}else{
			$reply['url'] = 'images/users/default.jpg';
		}
		return $reply;
	}
}
