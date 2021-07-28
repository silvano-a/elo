<?php

namespace App\Controller;

use App\EloCalculator\EloCalculator;
use App\Entity\EloLog;
use App\Entity\Wedstrijd;
use App\Form\WedstrijdType;
use App\Repository\SpelerRepository;
use App\Repository\WedstrijdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wedstrijd")
 */
class WedstrijdController extends AbstractController
{
    /**
     * @Route("/", name="wedstrijd_index", methods={"GET"})
     */
    public function index(WedstrijdRepository $wedstrijdRepository): Response
    {
        return $this->render('wedstrijd/index.html.twig', [
            'wedstrijds' => $wedstrijdRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="wedstrijd_new", methods={"GET","POST"})
     */
    public function new(Request $request, EloCalculator $calculator, SpelerRepository $spelerRepository): Response
    {
        $wedstrijd = new Wedstrijd();
        $form = $this->createForm(WedstrijdType::class, $wedstrijd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wedstrijd->setTimestamp(new \DateTimeImmutable());
            $entityManager = $this->getDoctrine()->getManager();

            $spelerHalfLog = new EloLog();
            $spelerHalfLog->setRating($wedstrijd->getSpelerHalf()->getRating());
            $spelerHalfLog->setWedstrijd($wedstrijd);
            $spelerHalfLog->setSpeler($wedstrijd->getSpelerHalf());
            $entityManager->persist($spelerHalfLog);

            $spelerHeelLog = new EloLog();
            $spelerHeelLog->setRating($wedstrijd->getSpelerHeel()->getRating());
            $spelerHeelLog->setSpeler($wedstrijd->getSpelerHeel());
            $spelerHeelLog->setWedstrijd($wedstrijd);
            $entityManager->persist($spelerHeelLog);

            $entityManager->persist($wedstrijd);
            $entityManager->flush();

            $speler1 = $spelerRepository->findOneById($wedstrijd->getSpelerHalf()->getId());
            $speler2 = $spelerRepository->findOneById($wedstrijd->getSpelerHeel()->getId());


            return $this->redirectToRoute('calculator_duo', [
                'speler1' => $speler1->getId(),
                'referAfter' => $speler2->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wedstrijd/new.html.twig', [
            'wedstrijd' => $wedstrijd,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/recalculate-elo/{speler1}/{referAfter}/", name="calculator_duo")
     */
    public function recalculateEloDuo(int $speler1, int $referAfter, SpelerRepository $spelerRepository, EntityManagerInterface $entityManager, EloCalculator $calculator): Response
    {
        $speler1 = $spelerRepository->findOneBy(['id'=> $speler1]);
        $speler1->setRating($calculator->calculateEloForSpeler($speler1));

        $entityManager->persist($speler1);
        $entityManager->flush();


        return $this->redirectToRoute('calculator_single', ['speler' => $referAfter]);
    }

    /**
     * @Route("/recalculate-elo/{speler}", name="calculator_single")
     */
    public function recalculateEloSingle( int $speler,  SpelerRepository $spelerRepository,EntityManagerInterface $entityManager, EloCalculator $calculator): Response
    {
        $speler = $spelerRepository->findOneBy(['id'=> $speler]);
        $speler->setRating($calculator->calculateEloForSpeler($speler));

        $entityManager->persist($speler);
        $entityManager->flush();

        return $this->redirectToRoute('speler_index');
    }



    /**
     * @Route("/{id}", name="wedstrijd_show", methods={"GET"})
     */
    public function show(Wedstrijd $wedstrijd): Response
    {
        return $this->render('wedstrijd/show.html.twig', [
            'wedstrijd' => $wedstrijd,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="wedstrijd_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Wedstrijd $wedstrijd): Response
    {
        $form = $this->createForm(WedstrijdType::class, $wedstrijd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('wedstrijd_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wedstrijd/edit.html.twig', [
            'wedstrijd' => $wedstrijd,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="wedstrijd_delete", methods={"POST"})
     */
    public function delete(Request $request, Wedstrijd $wedstrijd): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wedstrijd->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wedstrijd);
            $entityManager->flush();
        }

        return $this->redirectToRoute('wedstrijd_index', [], Response::HTTP_SEE_OTHER);
    }
}
