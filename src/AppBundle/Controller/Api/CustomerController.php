<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/customer")
 */
class CustomerController extends Controller
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   input="\AppBundle\Form\CustomerType",
     *   description="Create new Customer",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Route(path="/", name="app_api_customer_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $om = $this
                ->getDoctrine()
                ->getManagerForClass(Customer::class);
            $om->persist($customer);
            $om->flush();

            return new JsonResponse([
                'customer_id' => $customer->getId(),
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
