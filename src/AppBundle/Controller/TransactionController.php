<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/")
 */
class TransactionController extends Controller
{
    /**
     * @Route("/", name="app_transaction_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return [
            'transactions_route' => $this->generateUrl(
                'app_api_transaction_index',
                [],
                UrlGeneratorInterface::RELATIVE_PATH
            )
        ];
    }
}
