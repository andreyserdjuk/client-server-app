<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Transaction;
use AppBundle\Form\Model\TransactionFilters;
use AppBundle\Form\TransactionFiltersType;
use AppBundle\Form\TransactionType;
use AppBundle\Form\UpdateTransactionType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/transaction")
 */
class TransactionController extends Controller
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   description="Get transactions by filters",
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when the form has errors"
     *   },
     *   filters={
     *      {"name"="app_transaction_filters[customer]", "dataType"="integer"},
     *      {"name"="app_transaction_filters[amount]", "dataType"="float"},
     *      {"name"="app_transaction_filters[date]", "dataType"="date"},
     *      {"name"="app_transaction_filters[offset]", "dataType"="int"},
     *      {"name"="app_transaction_filters[limit]", "dataType"="int"}
     *   }
     * )
     *
     * @Route("/", name="app_api_transaction_index")
     * @Method("GET")
     * @Cache(expires="+1 hour")
     */
    public function indexAction(Request $request)
    {
        $transactionFilters = new TransactionFilters();
        $form = $this->createForm(TransactionFiltersType::class, $transactionFilters, ['method' => 'GET']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
            $transactions = $transactionRepo
                ->getByParams(
                    $transactionFilters->getCustomer(),
                    $transactionFilters->getAmount(),
                    $transactionFilters->getDate(),
                    $transactionFilters->getOffset(),
                    $transactionFilters->getLimit()
                );

            $result = [];
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                $result[] = [
                    'transaction_id' => $transaction->getId(),
                    'customer_id' => $transaction->getCustomer()->getId(),
                    'amount' => $transaction->getAmount(),
                    'date' => $transaction->getDate()->format('Y-m-d'),
                ];
            }

            return new JsonResponse([
                'data' => $result,
                'total' => $transactionRepo->getTotal(
                    $transactionFilters->getCustomer(),
                    $transactionFilters->getAmount(),
                    $transactionFilters->getDate()
                ),
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getCause()->getPropertyPath()] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description="Get transaction",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @ParamConverter(
     *     "transaction",
     *     class="AppBundle:Transaction",
     *     options={
     *       "mapping": {"customer_id": "customer", "id": "id"},
     *     }
     * )
     * @Route("/{customer_id}/{id}", name="app_api_transaction_get")
     * @Method("GET")
     * @Cache(expires="+1 hour")
     */
    public function getAction(Transaction $transaction)
    {
        return new JsonResponse([
            'transaction_id' => $transaction->getId(),
            'amount' => $transaction->getAmount(),
            'date' => $transaction->getDate()->format('Y-m-d'),
        ]);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   input="\AppBundle\Form\TransactionType",
     *   description="Create new transaction",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Route("/", name="app_api_transaction_create")
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $om = $this
                ->getDoctrine()
                ->getManagerForClass(Transaction::class);
            $om->persist($transaction);
            $om->flush();

            return new JsonResponse([
                'transaction_id' => $transaction->getId(),
                'customer_id' => $transaction->getCustomer()->getId(),
                'amount' => $transaction->getAmount(),
                'date' => $transaction->getDate()->format('Y-m-d'),
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }


    /**
     * @ApiDoc(
     *   resource = true,
     *   input="\AppBundle\Form\UpdateTransactionType",
     *   description="Edit existent transaction",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @ParamConverter("transaction", class="AppBundle:Transaction")
     * @Route("/{id}", name="app_api_transaction_update")
     * @Method("PUT")
     */
    public function putAction(Request $request, Transaction $transaction)
    {
        $form = $this->createForm(UpdateTransactionType::class, $transaction, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $om = $this
                ->getDoctrine()
                ->getManagerForClass(Transaction::class);
            $om->persist($transaction);
            $om->flush();

            return new JsonResponse([
                'transaction_id' => $transaction->getId(),
                'customer_id' => $transaction->getCustomer()->getId(),
                'amount' => $transaction->getAmount(),
                'date' => $transaction->getDate()->format('Y-m-d'),
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description="Delete existent transaction",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @ParamConverter("transaction", class="AppBundle:Transaction")
     * @Route("/{id}", name="app_api_transaction_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Transaction $transaction)
    {
        $om = $this
            ->getDoctrine()
            ->getManagerForClass(Transaction::class);
        $om->remove($transaction);
        $om->flush();

        return new JsonResponse(['success' => true]);
    }
}
