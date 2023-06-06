<?php

namespace App\Controller;

use App\Form\CSRFormType;
use App\Service\CSRGenerator;
use App\DTO\CSRData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        
        $csrOutput = null;
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $csrOutput = $this->csrGenerator->generate($csrData);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('csr/index.html.twig', [
            'form' => $form->createView(),
            'csrOutput' => $csrOutput,
        ]);
    }
}