<?php

namespace Rto\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//APIs
use Rto\CoreBundle\APIs\Core;

class ProfileController extends Controller
{
    public function editAction()
	{
		$user       = Core::getUser($this);
		$user       = $user['user'];
		$data['id'] = $user -> getId();
		$get        = Core::existsPicture($data);
		$imageUrl   = $get['url'];
		return $this -> render('RtoProfileBundle:profile:edit.html.twig', array('user'=>$user, 'image' => $imageUrl));
	}
}
