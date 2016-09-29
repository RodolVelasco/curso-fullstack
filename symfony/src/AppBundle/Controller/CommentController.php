<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Video;
use BackendBundle\Entity\Comment;

class CommentController extends Controller
{
    public function newAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);
        
        if($authCheck == true)
        {
            $identity = $helpers->authCheck($hash, true);
            $json = $request->get("json", null);
            
            if($json != null)
            {
                $params = json_decode($json);
                $createdAt = new \DateTime("now");
                $user_id    = (isset($identity->sub)) ? $identity->sub : null;
                $video_id   = (isset($params->video_id)) ? $params->video_id : null;
                $body       = (isset($params->body)) ? $params->body : null;
                
                
                if($user_id != null && $video_id != null){
                    $em = $this->getDoctrine()->getManager();
                    $user = $em->getRepository("BackendBundle:User")->findOneBy(
                                array(
                                    "id" => $user_id
                                )
                            );
                    $video = $em->getRepository("BackendBundle:Video")->findOneBy(
                                array(
                                    "id" => $video_id
                                )
                            );
                    
                    $comment = new Comment();
                    $comment->setUser($user);
                    $comment->setVideo($video);
                    $comment->setBody($body);
                    $comment->setCreatedAt($createdAt);
                    $em->persist($comment);
                    $em->flush();
                    
                    $data = array(
                                "status"    => "success",
                                "code"      => 200,
                                "msg"       => "Comment created successfully"
                            );
                }else{
                    $data = array(
                        "status"    => "error",
                        "code"      => 400,
                        "msg"       => "Comment not created, user or video are missing"
                    );
                }
            }else{
                $data = array(
                        "status"    => "error",
                        "code"      => 400,
                        "msg"       => "Comment not created, params failed"
                    );
            }
        }else{
            $data = array(
                        "status"    => "error",
                        "code"      => 400,
                        "msg"       => "Authentication failed"
                    );
        }
        
        return $helpers->json($data);
    }
    
    public function deleteAction(Request $request, Comment $comment_id = null)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);
        
        if($authCheck == true)
        {
            $identity = $helpers->authCheck($hash, true);
            
            $comment = $comment_id;
            
            $user_id    = (isset($identity->sub)) ? $identity->sub : null;
            
            if(is_object($comment) && count($comment) == 1)
            {
                if( isset($identity->sub) && 
                    ($identity->sub == $comment->getUser()->getId()) ||
                     $identity->sub == $comment->getVideo()->getUser()->getId()
                ){
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($comment);
                    $em->flush();
                    
                    $data = array(
                                "status"    => "success",
                                "code"      => 200,
                                "msg"       => "Comment deleted successfully"
                            );
                }else
                    $data = array(
                                "status"    => "error",
                                "code"      => 400,
                                "msg"       => "Comment not deleted, you are not the owner"
                            );
                }
        }else{
            $data = array(
                        "status"    => "error",
                        "code"      => 400,
                        "msg"       => "Authentication failed"
                    );
        }
        
        return $helpers->json($data);
    }
}