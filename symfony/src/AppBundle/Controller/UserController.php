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
                //cifrar password
                $pwd = hash('sha256', $password);
                
                $user->setPassword($pwd);
                
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
                    $data["code"]   =   "200";
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
    
    public function editAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);
        
        if($authCheck == true)
        {
            // decodifica los datos que vienen en el hash
            $identity = $helpers->authCheck($hash, true);
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('BackendBundle:User')->findOneBy(
                        array(
                            "id" => $identity->sub
                        )
                    );
            
            
            $json = $request->get("json", null);
            $params = json_decode($json);
            
            $data = array(
                        "status" => "error",
                        "code"   => 400,
                        "msg"    => "User not updated."
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
                
                if($email != null && count($validate_email) == 0 && $name != null && strlen(trim($name))>0 && $surname != null && strlen(trim($surname))>0)
                {
                    $user->setCreatedAt($createdAt);
                    $user->setImage($image);
                    $user->setRole($role);
                    $user->setEmail($email);
                    $user->setName($name);
                    $user->setSurname($surname);
                    
                    
                    if($password != null)
                    {
                        //cifrar password
                        $pwd = hash('sha256', $password);
                        $user->setPassword($pwd);
                    }
                    
                    //email debe ser único
                    $em = $this->getDoctrine()->getManager();
                    $isset_user = $em->getRepository('BackendBundle:User')->findBy(
                            array(
                                "email" => $email
                            )
                        );
                    if(count($isset_user) == 0 || $identity->email == $email){
                        $em->persist($user);
                        $em->flush();
                        
                        $data["status"] =   "success";
                        $data["code"]   =   "200";
                        $data["msg"]    =   "User updated !!";
                        
                    }else{
                        $data = array(
                            "status" => "error",
                            "code"   => 400,
                            "msg"    => "User not updated, duplicated !!"
                        );
                    }
                }
            }else{
                $data = array(
                            "status"    => "error",
                            "code"      => 400,
                            "msg"       => "Authorization not valid, param failed"
                        );
            }
        }else{
            $data = array(
                            "status"    => "error",
                            "code"      => 400,
                            "msg"       => "Authorization not valid"
                        );
        }
        
        return $helpers->json($data);
    }
    
    public function uploadImageAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);
        
        if($authCheck)
        {
            // decodifica los datos que vienen en el hash
            $identity = $helpers->authCheck($hash, true);
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('BackendBundle:User')->findOneBy(
                        array(
                            "id" => $identity->sub
                        )
                    );
                    
            //upload file
            $file = $request->files->get("image");
            
            if(!empty($file) && $file != null)
            {
                $ext = $file->guessExtension();
                
                if($ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "gif")
                {
                    $file_name = time() . "." . $ext;
                    $file->move("uploads/users", $file_name);
                    
                    $user->setImage($file_name);
                    $em->persist($user);
                    $em->flush();
                    
                    $data = array(
                                "status"    =>  "success",
                                "code"      =>  200,
                                "msg"       =>  "Image for user uploaded success"
                            );
                }else{
                    $data = array(
                                "status"    =>  "error",
                                "code"      =>  400,
                                "msg"       =>  "File not valid"
                            );
                }
            }else{
                $data = array(
                            "status"    =>  "error",
                            "code"      =>  400,
                            "msg"       =>  "Image not uploaded"
                        );
            }
        }else{
            $data = array(
                        "status"    =>  "error",
                        "code"      =>  400,
                        "msg"       =>  "Authorization not valid"
                    );
        }
        
        return $helpers->json($data);
    }
}