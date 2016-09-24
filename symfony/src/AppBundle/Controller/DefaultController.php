<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            $getHash = (isset($params->getHash)) ? $params->getHash : null;
            
            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "This email is not valid.";
            
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);
            
            if(count($validate_email) == 0 && $password != null)
            {
                if($getHash == NULL)
                {
                    $signup = $jwt_auth->signup($email, $password);
                    //$signup = $jwt_auth->signup($email, $password, "hash");
                }else{
                    $signup = $jwt_auth->signup($email, $password, true);
                }
                
                //codifica un objeto php a json
                return new JsonResponse($signup);
            }else{
                return $helpers->json(array("status"=>"error","data"=>"Login not valid."));
            }
        }else{
           return $helpers->json(array("status"=>"error","data"=>"Send json with post."));
        }
        
        die();
    }
    
    public function pruebasAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization", null);
        $check = $helpers->authCheck($hash);
        //$check = $jwt_auth->checkToken($hash);
        
        var_dump($check);
        die();
        /*
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('BackendBundle:User')->findAll();
        */
        //$users = array("id"=>1,"nombre"=>"marduk");
        //return $helpers->json($users);
        //dump($users);
        //die();
    }
    

}
