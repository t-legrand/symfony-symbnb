<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /** <\d+>?1 -> requirement, nombre(s) avec par défaut 1
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * 
     * @param $page
     * @param AdRepository $repo
     * @return void
     */
    public function index(AdRepository $repo, $page = 1, PaginationService $pagination)
    {
        /* Méthode find : permet de retrouver un enregistrement à partir d'un identifiant
        $ad = $repo->find(884);
        

        $ad = $repo->findOneBy([
            'title' => "Quisquam repellendus suscipit perferendis autem fugit quis.(corrigée)"
        ]);

        $ads = $repo->findBy([], [], 5, 0);

        //dump($ad);
        dump($ads);*/

        $pagination->setEntityClass(Ad::class)
                    ->setPage($page);
        
        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success', "Les modifications de l'annonce <strong>{$ad->getTitle()} </strong> a bien été enregisrée !");
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager) {
        if(count($ad->getBookings()) > 0) {
            $this->addFlash('warning', "L'annonce <strong>{$ad->getTitle()} </strong> ne peut pas être supprimée car elle possède déjà des réservations !");
        }
        else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()} </strong> a bien été supprimée !");
        }
        return $this->redirectToRoute('admin_ads_index');
    }
}
