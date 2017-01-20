<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class APIController extends Controller
{
    
    
     
    
    /** 
     * @Route("/api/directory{path}", requirements={"path":".+"},  options={"utf8":true}, name="api_directory")
     */
    public function directoryAction($path = '/')
    {            
        $api = $this->getApi();
        
        return  \Symfony\Component\HttpFoundation\JsonResponse::create(         
               $api->directoryContents($path)                   
        );
    }
    
    /**
     * @Route("/api/delete{path}" , requirements={"path":".+"},  options={"utf8":true}, name="api_delete")
     */
    public function deleteAction($path)
    {
         $api = $this->getApi();
         
         return  \Symfony\Component\HttpFoundation\JsonResponse::create( [
             'result'   => $api->delete($path),
         ] );
    }
    
    /**
     * 
     * @Route("/api/download{path}"  , requirements={"path":".+"},  options={"utf8":true}, name="api_download")
     * @param string $path
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function downloadFileAction($path) {
        $api = $this->getApi();
        
        return  \Symfony\Component\HttpFoundation\JsonResponse::create( [
             'result'   => $api->downloadFile($path) , 
         ] );
    }
    
    

    
    /**
     * 
     * @return \AppBundle\Service\Api
     */
    private function getApi() {
        return $this->get('service_api');
    }

}
