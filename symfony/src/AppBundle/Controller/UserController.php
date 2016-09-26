<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\User;

class UserController extends Controller
{
    public function newAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        
        $json = $request->get("json", null);
        $params = json_decode($json);
        
        $data = array(
                    "status" => "error",
                    "code"   => 400,
                    "msg"    => "User not created."
                );
        
        if($json != null)
        {
            $createdAt = new \DateTime("now");
            $image = null;
            $role = "user";
        
            $email = (isset($params->email)) ? $params->email : null;
            //$name = (isset($params->name) && ctype_alpha($params->name)) ? $params->name : null;
            //$surname = (isset($params->surname) && ctype_alpha($params->surname)) ? $params->surname : null;
            $name = (isset($params->name) && preg_match('/^[\p{Latin}\s]+$/u', $params->name)) ? $params->name : null;
            $surname = (isset($params->surname) && preg_match('/^[\p{Latin}\s]+$/u', $params->surname)) ? $params->surname : null;
            
            $password = (isset($params->password)) ? $params->password : null;
            
            //validación de email
            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "This email is not valid.";
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);
            
            if($email != null && count($validate_email) == 0 && $password != null && $name != null && strlen(trim($name))>0 && $surname != null && strlen(trim($surname))>0)
            {
                $user = new User();
                $user->setCreatedAt($createdAt);
                $user->setImage($image);
                $user->setRole($role);
                $user->setEmail($email);
                $user->setName($name);
                $user->setSurname($surname);
                $user->setPassword($password);
                
                //email debe ser único
                $em = $this->getDoctrine()->getManager();
                $isset_user = $em->getRepository('BackendBundle:User')->findBy(
                        array(
                            "email" => $email
                        )
                    );
                if(count($isset_user) == 0){
                    $em->persist($user);
                    $em->flush();
                    
                    $data["status"] =   "success";
                    $data["msg"]    =   "New user created !!";
                    
                }else{
                    $data = array(
                        "status" => "error",
                        "code"   => 400,
                        "msg"    => "User not created, duplicated !!"
                    );
                }
                
            }else{
                
            }
        }
        
        return $helpers->json($data);
    }
}
