<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Kitpages\DataGridBundle\Grid\GridConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;

use AppBundle\Grid\UserGrid;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {  
        return $this->render('default/index.html.twig', [ ]);
    }
    
    /**
     * @Route("/vue", name="homepage2")
     */
    public function indexVueAction(Request $request)
    {  
        return $this->render('default/index_vue.html.twig', [ ]);
    }
    
    /**
     * @Route("/grid.json", name="grid_data")
     */
    public function gridDataAction(Request $request) {
        
        $page = $request->get('page', 1); 
        $limit = $request->get('limit', 10);
        $offset = $request->get('start', 0) ? : max(0, $page - 1) * $limit;
        
        $em = $this->get('doctrine')->getEntityManager();
         
        $queryBuilder = $em->createQueryBuilder()
            ->select('u.id as u_id, u.username as u_username,  u.email as u_email, e.title as e_title, groupconcat(r.title) as r_title')
            ->from('AppBundle:User', 'u')
            ->leftJoin('u.education', 'e') 
            ->leftJoin('u.regions', 'r')
            ->groupBy('u.id')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );
        ;
        
        if(!empty($sort = $request->get('sort', null) )) {
            $queryBuilder->addOrderBy($sort, $request->get('dir', null));
        }
         
        $total = $em->createQueryBuilder()
            ->select('count(u)')
            ->from('AppBundle:User', 'u')
            ->getQuery()
            ->getSingleScalarResult()
        ; 
         
        $response = new JsonResponse([
            'items'     => $queryBuilder->getQuery()->getArrayResult(),  
            'total'     => $total,
            'page'      => $page,
            'success'   => true,
        ]); 
        $response->setCallback( $request->get('callback', null)); 
        return $response;
    }
    
 
}
