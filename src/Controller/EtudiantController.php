<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




#[Route('/etudiant')]
class EtudiantController extends AbstractController
{
    #[Route('/', name: 'etudiant')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo= $doctrine->getRepository(Etudiant::class);
        $etudiant = $repo->findAll();

        return $this->render('etudiant/index.html.twig', [
            'etds' => $etudiant
        ]);
    }

    #[Route('/remove/{id<\d+>?0}', name: 'removeEtudiant')]
    public function remove(ManagerRegistry $doctrine, Etudiant $etd = null): RedirectResponse
    {
        if (!$etd) {
            $this->addFlash('error', 'student non existent');

        } else {
            $manager = $doctrine->getManager();
            $manager->remove($etd);
            $this->addFlash('success', 'student removed successfully');
            $manager->flush();

        }
        return $this->redirectToRoute('etudiant');
    }
    #[Route('/edit/{id?0}', name: 'etudiantform')]
    public function addEtudiantForm(Etudiant $etudiant = null, ManagerRegistry $doctrine, Request $request): Response
    {
        $new = false;
        //$this->getDoctrine() : Version Sf <= 5
        if (!$etudiant) {
            $new = true;
            $etudiant = new Etudiant();
        }

        // $etudiant est l'image de notre formulaire
        $form = $this->createForm(EtudiantType::class, $etudiant);
        // On va aller traiter la requete
        $form->handleRequest($request);
        //Si le formulaire a été soumis
        if($form->isSubmitted()) {
            // si oui,
            // on va appender l'etudiant dans la bd
            $manager = $doctrine->getManager();
            $manager->persist($etudiant);

            $manager->flush();
            // Afficher un mssage de succès
            if($new) {
                $message = " a été ajouté avec succès !";
            } else {
                $message = " a été mis à jour avec succès !";
            }
            $this->addFlash('success',$etudiant->getNom(). $message );
            // Rediriger vers la liste des etudiants
            return $this->redirectToRoute('etudiant');
        }

        //Sinon on affiche notre formulaire
        return $this->render('etudiant/from.html.twig', [
            'form' => $form->createView()
        ]);

    }

//    #[Route('/edit/{id?0}', name: 'etudiantform')]
//    public function addEtudiantForm(ManagerRegistry $doctrine , Request $request, Etudiant $etd=null): Response
//    {
//        if (!$etd){
//            $etd = new Etudiant();
//        }
//
//        $form = $this->createForm(EtudiantType::class, $etd);

//        $form->handleRequest($request);
//        if ($form->isSubmitted() ){
//
//            $manager = $doctrine->getManager();
//            $manager->persist($etd);
//            $manager->flush();
//            $this->addFlash('success','form submitted successfully');
//            return $this->redirectToRoute('etudiant');
//        }
//        else{
//            return $this->render('etudiant/from.html.twig', [
//                'form' => $form->createView()
//            ]);
//        }


//    }




}

