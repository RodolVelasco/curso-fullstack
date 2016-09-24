<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
    
    public function loginAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $jwt_auth = $this->get("app.jwt_auth");
        
        $json = $request->get("json", null);
        
        
        if($json != null)
        {
            $params = json_decode($json);
            
            $email = (isset($params->email)) ? $params->email : null;
            $password = (isset($params->password)) ? $params->password : null;
            
            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "This email is not valid.";
            
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);
            
            if(count($validate_email) == 0 && $password != null)
            {
                $signup = $jwt_auth->signup($email, $password);
                return $helpers->json($signup);
            }else{
                echo "Data incorrect.";
            }
        }else{
            echo "echo with post";
            die();
        }
        
        die();
    }
    
    public function pruebasAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('BackendBundle:User')->findAll();
        //$users = array("id"=>1,"nombre"=>"marduk");
        return $helpers->json($users);
        //dump($users);
        //die();
    }
}
