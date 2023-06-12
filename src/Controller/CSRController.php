<?php

namespace App\Controller;

use App\Form\CSRFormType;
use App\Service\CSRGenerator;
use App\DTO\CSRData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CSRController extends AbstractController
{
    private $csrGenerator;

    public function __construct(CSRGenerator $csrGenerator)
    {
        $this->csrGenerator = $csrGenerator;
    }

    #[Route('/csr', name: 'app_csr')]
    public function index(Request $request): Response
    {
        $csrData = new CSRData();
        $form = $this->createForm(CSRFormType::class, $csrData);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $csrOutput = $this->csrGenerator->generate($csrData);
                
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'success' => true,
                        'csr' => $csrOutput,
                        'metadata' => [
                            'commonName' => $csrData->commonName,
                            'organization' => $csrData->organization,
                            'organizationalUnit' => $csrData->organizationalUnit,
                            'locality' => $csrData->locality,
                            'state' => $csrData->state,
                            'country' => $csrData->country,
                            'email' => $csrData->email,
                            'keySize' => $csrData->keySize
                        ]
                    ]);
                }
                
                return $this->render('csr/index.html.twig', [
                    'form' => $form->createView(),
                    'csrOutput' => $csrOutput
                ]);
            } catch (\Exception $e) {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage()
                    ], 400);
                }
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('csr/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}