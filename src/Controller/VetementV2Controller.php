<?php

namespace App\Controller;

use App\Entity\Vetement;
use App\Form\VetementFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class VetementV2Controller extends AbstractController
{
    /**
     * @Route("/v2/vetement", name="vetement_v2_index", methods={"GET"})
     */
    public function index()
    {
        return $this->redirectToRoute('vetement_v2_list');
    }

    /**
     * @Route("/v2/list/vetements", name="vetement_v2_list", methods={"GET"})
     */
    public function list() {
        $vetements = $this->getDoctrine()->getRepository(Vetement::class)->findBy([], ['id' => 'ASC']);
        return $this->render('vetement_v2/index.html.twig', ['vetements' => $vetements]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */

    /**
     * @Route("/v2/add/vetement", name="vetement_v2_add", methods={"GET","POST"})
     */
    public function add(Request $request) {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(VetementFormType::class, Null, [
            'action' => $this->generateUrl('vetement_v2_add'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $vetement = $form->getData();

            $manager->persist($vetement);
            $manager->flush();

            $this->addFlash('info_vetement', 'Vetement ajouté !');
            return $this->redirectToRoute('vetement_v2_list');
        }

        return $this->render('vetement_v2/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/v2/delete/vetement", name="vetement_v2_delete", methods={"DELETE"})
     */
    public function delete(Request $request) {
        if(!$this->isCsrfTokenValid('vetement_delete', $request->get('token'))) {
            throw new  InvalidCsrfTokenException('Invalid CSRF token formulaire vetement');
        }

        $manager = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $vetement = $manager->getRepository(Vetement::class)->find($id);
        if(!$vetement) throw $this->createNotFoundException('No vetement found for id' . $id);

        $this->addFlash('info_vetement', 'Vetement supprimé !');

        $manager->remove($vetement);
        $manager->flush();

        return $this->redirectToRoute('vetement_v2_list');
    }

    /**
     * @Route("v2/edit/{id}/vetement/", name="vetement_v2_edit", methods={"GET"})
     * @Route("v2/edit/vetement/", name="vetement_v2_edit_valid", methods={"PUT"})
     */
    public function edit(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $id = $request->get('id');

        $vetement = $this->getDoctrine()->getRepository(Vetement::class)->find($id);
        if(!$vetement) throw $this->createNotFoundException('No vetement found for id ' .$id);

        $form = $this->createForm(VetementFormType::class,$vetement, [
            'action' => $this->generateUrl('vetement_v2_edit_valid',['id'=>$id]),
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $vetement = $form->getData();
            $manager->persist($vetement);
            $manager->flush();
            $this->addFlash('info_vetement', 'Vetement modifié !');
            return $this->redirectToRoute('vetement_v2_list');
        }
        return $this->render('vetement_v2/edit.html.twig', [
            'form'=> $form->createView(),
            'vetement' => $vetement]);
    }
}
