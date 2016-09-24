<?php

namespace AppBundle\Services;
use Firebase\JWT\JWT;

class JwtAuth
{
    public $manager;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    
    public function signup($email, $password, $getHash = NULL)
    {
        $key = "clave-secreta";
        
        $user = $this->manager->getRepository('BackendBundle:User')->findOneBy(
                    array(
                            "email" => $email,
                            "password" => $password
                    )
                );
        
        $signup = false;
        if(is_object($user))
        {
            $signup = true;
        }
        
        if($signup == true)
        {
            return array("status"=>"success", "data"=>"Login success.");
        }else{
            return array("status"=>"error", "data"=>"Login failed.");
        }
    }
}