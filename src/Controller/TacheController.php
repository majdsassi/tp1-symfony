<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Tache ; 
use  Doctrine\ORM\EntityManagerInterface;
use App\Repository\TacheRepository;

final class TacheController extends AbstractController
{    #[Route('/tache/nouveau', name: 'app_tache_nouveau')]
    public function nouveau(EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setTitre('Ma première tache');
        $tache->setDescription('Ceci est la description de ma première tache créée avec Doctrine.');
        $tache->setDateCreation(new \DateTime());
        $tache->setTerminee(false);

        $em->persist($tache);
        $em->flush();

        return new Response("Tache créée avec l'id : " . $tache->getId());
    }
    #[Route('/tache', name: 'app_tache')]
    public function index(TacheRepository $tacheRepository): Response
    {
        $taches = $tacheRepository->findAll();

        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }
    #[Route('/tache/{id}', name: 'app_tache_detail', requirements: ['id' => '\d+'])]
public function detail(Tache $tache): Response
{
    return $this->render('tache/detail.html.twig', [
        'tache' => $tache,
    ]);
}
#[Route('/tache/{id}/terminer', name: 'app_tache_terminer', requirements: ['id' => '\d+'])]
public function terminer(Tache $tache, EntityManagerInterface $em): Response
{
    $tache->setTerminee(true);
    $em->flush();

    return $this->redirectToRoute('app_tache_detail', ['id' => $tache->getId()]);
}
}
