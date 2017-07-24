<?php

namespace Rto\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
//APIs
use Rto\CoreBundle\APIs\Core;

class ProfileAjaxController extends Controller
{
	public function send($array){
		$reply = new Response(json_encode($array));
		return $reply;
	}
    public function updateAction()
	{
		$type			  = 3;
		
		$request 		  = $this -> getRequest() -> request;
		$data['action']   = $request -> get('action');
		
		switch($data['action']){
			case 'uploaded':{
				$data['file'] = $_FILES['file'];
				$state        = Core::uploadPicture($data, $this);
				break;
			}
			case 'deleted':{
				$state        = Core::deletePicture($this);
				break;
			}
			case 'taked':{
				$state        = Core::movePicture($this);
				break;
			}
		}
		$data['password'] = $request -> get('password');
		$em 			  = $this -> getDoctrine() -> getManager();
		$get 			  = Core::getUser($this);
		$user 			  = $get['user'];
		
		$user 			  -> setPassword(Core::encode($data['password']));
		$user			  -> setDateupdate(date_create(date("Y-m-d H:i:s")));
		$em 			  -> persist($user);
		$em 			  -> flush();
		
		$type 			  = 4;
		
		$array = array('state'=> true,'type'=>$type);
		return $this -> send($array);
	}
    public function loadPictureAction()
	{
		$user = Core::getUser($this);
		$user = $user['user'];
		
		if(file_exists('images/tmp/'.md5($user -> getId()).'.jpg')){
			unlink('images/tmp/'.md5($user -> getId()).'.jpg');
		}
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$folder   = 'images/tmp/';
			$filename = md5($user -> getId()).'.jpg';
			$original = $folder.$filename;
			$input    = file_get_contents('php://input');
			
			
			if(md5($input) == '7d4df9cc423720b7f1f3d672b89362be'){
				exit;
			}
			$result   = file_put_contents($original, $input);
			if (!$result){
				echo '{
				"error"		: 1,
				"message"	: "Failed save the image. Make sure you chmod the uploads folder and its subfolders to 777."
				}';
				exit;
			}
	
			$info      = getimagesize($original);
			if($info['mime'] != 'image/jpeg'){
				unlink($original);
				exit;
			}
				
			// Moving the temporary file to the originals folder:
			rename($original,'../web/images/tmp/'.$filename);
			$src = '../../images/tmp/'.$filename;
			/*
			$size  = getimagesize($original);
			$width = $size[0];
			$height = $size[0];
			$max_width = 80;
			$max_height = 90; 
			$percent = 0.2;
			header('Content-Type: image/jpeg');
			
			$new_width = 220;
			$new_height = floor( $height * ( $new_width / $width ) );

			
			if ($height > $max_height) {
		        $width = ($max_height / $height) * $width;
		        $height = $max_height;
		    }
		    if ($width > $max_width) {
		        $height = ($max_width / $width) * $height;
		        $width = $max_width;
		    }
					
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$image = @imagecreatefromjpeg($original);
			imagecopyresized($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);
			imagejpeg($image_p, $original);
			imagedestroy($image_p);*/
		}
		else{
			exit;
		}
		$array = array('state'=> true,'src'=>$src);
		return $this -> send($array);
	}
}
