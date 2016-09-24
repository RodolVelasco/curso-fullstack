<?php

namespace AppBundle\Services;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class Helpers
{
    public function json($data)
    {
        $normalizers = array(new GetSetMethodNormalizer());
        $encoders = array("json" => new JsonEncoder());
        
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($data, 'json');
        
        $response = new Response();
        $response->setContent($json);
        $response->headers->set("Content-Type", "application/json");
        
        return $response;
    }
}