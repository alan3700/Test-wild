<?php

namespace App\Controller;

use App\Form\ArgoType;
use App\Entity\Argonautes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArgoController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("", name="argo")
     */
    public function index(Request $request): Response
    {

        $argo = $this->em->getRepository(Argonautes::class)->findAll();
        $argonautes = new Argonautes;
        $addArgoForm = $this->createForm(ArgoType::class, $argonautes);
        $addArgoForm->handleRequest($request);
        if ($addArgoForm->isSubmitted() && $addArgoForm->isValid()) {
            $argonautes = $addArgoForm->getData();
            $this->em->persist($argonautes);
            $this->em->flush();
            return $this->redirectToRoute("argo");
        }
        return $this->render('argo/index.html.twig', [
            'argo'=>$argo,
            'add_argo_form' => $addArgoForm->createView()
        ]);
    }
}
