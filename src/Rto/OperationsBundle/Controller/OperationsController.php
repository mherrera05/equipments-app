<?php

namespace Rto\OperationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OperationsController extends Controller
{
    public function rigActivityAction()
    {
        return $this->render('RtoOperationsBundle:operations:index.html.twig');
    }
}
