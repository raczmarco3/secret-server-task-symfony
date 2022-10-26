<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Secret;
use App\Form\AddSecretFormType;
use App\Form\GetSecretFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SecretService;
use Doctrine\Persistence\ManagerRegistry;

class SecretController extends AbstractController
{
    #[Route('/secret')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $secretService = new SecretService();
        $secret = new Secret();
        $addSecretForm = $this->createForm(AddSecretFormType::class, $secret);
        $addSecretForm->handleRequest($request);        

        if($addSecretForm->isSubmitted() && $addSecretForm->isValid()) {
            $secret = $addSecretForm->getData();            
            $message = $secretService->saveSecret($doctrine, $secret);

            return $this->render('/secret/index.html.twig', [
                'secretFormMessage' => $message
            ]);
        }

        $getSecret = array();
        $getSecretForm = $this->createForm(GetSecretFormType::class, $getSecret);
        $getSecretForm->handleRequest($request);

        if($getSecretForm->isSubmitted()) {            
            $getSecret = $getSecretForm->getData();
            return $this->redirectToRoute('show_secret', ['hash' => $getSecret['hash'], 'accept' => $getSecret["options"]]);
        }

        return $this->renderForm('secret/index.html.twig', [
            'addSecretForm' => $addSecretForm,
            'getSecretForm' => $getSecretForm,
        ]);        
    }
    
    #[Route('/secret/{hash}', name: "show_secret", methods: ['GET'])]
    public function showSecretContent(Request $request, ManagerRegistry $doctrine, String $hash): Response
    {
        if(isset($_GET["accept"])) {
            $contentType = $_GET["accept"];
        } else {
            $contentType = $request->headers->get('Accept');
        }

        $secretService = new SecretService();
        
        if(!str_contains($contentType, ';')) {            
            $response = new Response($secretService->getSecret($doctrine, $hash, $contentType));
            $response->headers->set('Content-Type', $contentType);
            return $response;
        } else {
            return new Response("Not allowed Content-Type. Allowed Content-Types are: JSON or XML.");
        }

        return new Response("Something went wrong!");
    } 

    #[Route('/')]
    public function mainPage(Request $request): Response
    {
        $getSecret = array();
        $getSecretForm = $this->createForm(GetSecretFormType::class, $getSecret);
        $getSecretForm->handleRequest($request);

        if($getSecretForm->isSubmitted()) {            
            $getSecret = $getSecretForm->getData();
            return $this->redirectToRoute('show_secret', ['hash' => $getSecret['hash'], 'accept' => $getSecret["options"]]);
        }

        return $this->renderForm('index.html.twig', [
            'getSecretForm' => $getSecretForm,
        ]);
    }

    /*
    #[Route('/secret/{hash}', methods: ['GET'], condition: "request.headers.get('Content-Type') === 'application/json'")]
    public function showSecretJson(Request $request, ManagerRegistry $doctrine, String $hash): Response
    {
        $secretService = new SecretService();
        $response = new Response($secretService->getSecret($doctrine, $hash, 'application/json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/secret/{hash}', methods: ['GET'], condition: "request.headers.get('Content-Type') === 'application/xml'")]
    public function showSecretXml(Request $request, ManagerRegistry $doctrine, String $hash): Response
    {
        $secretService = new SecretService();
        $response = new Response($secretService->getSecret($doctrine, $hash, 'application/xml'));
        $response->headers->set('Content-Type', 'application/xml');
        return $response;
    }

    #[Route('/secret/{hash}')]
    public function showSecretNotAllowedContentType(String $hash): Response
    {
        return new Response("Not allowed Content-Type. Allowed Content-Types are: JSON or XML.");
    }    
    */
}
