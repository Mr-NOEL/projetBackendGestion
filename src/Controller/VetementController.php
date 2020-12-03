<?php

namespace App\Controller;

use App\Entity\Vetement;
use App\Entity\Type;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class VetementController extends AbstractController
{
    /**
     * @Route("/vetement",name="vetement_index",methods={"GET"})
     */
    public function index()
    {
        return $this->redirectToRoute('vetement_list');
    }

    /**
     * @Route("/list/vetements", name="vetement_list",methods={"GET"})
     */
    public function list()
    {
        $vetements = $this->getDoctrine()->getRepository(Vetement::class)->findBy([],['id' => 'ASC']);
        return $this->render('vetement/index.html.twig', ['vetements'=>$vetements]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */

    /**
     * @Route("/add/vetement", name="vetement_add", methods={"GET","POST"})
     */
    public function add(Request $request) {
        $manager = $this->getDoctrine()->getManager();

        $types = $this->getDoctrine()->getRepository(Type::class)->findBy([],['libelle' => 'ASC']);

        if($request->getMethod() == "GET") {
            return $this->render('vetement/add.html.twig', ['types' => $types]);
        }

        if(!$this->isCsrfTokenValid('form_vetement', $request->get('token'))) {
            throw new  InvalidCsrfTokenException('Invalid CSRF token formulaire vetement');
        }

        $donnees['id'] = $request->request->get('id');
        $donnees['type_id'] = $request->request->get('type_id');
        $donnees['descriptif'] = $request->request->get('descriptif');
        $donnees['prixDeBase'] = $request->request->get('prixDeBase');
        $donnees['taille'] = $request->request->get('taille');
        $donnees['dateAchat'] = $request->request->get('dateAchat');

        $errors = $this->validator($donnees);
        dump($errors);

        $vetements = NULL;

        if (empty($errors)) {
            $vetements = new Vetement();

            $vetements->setDescriptif($_POST['descriptif'])
                ->setPrixDeBase($_POST['prixDeBase'])
                ->setTaille($_POST['taille'])
                ->setDateAchat(\DateTime::createFromFormat('d/m/Y', $_POST['dateAchat']))
                ->setType($this->getDoctrine()->getRepository(Type::class)->findOneBy([],['libelle' => 'ASC']));

            $this->addFlash('info_vetement', 'Vetement ajouté !');

            $manager->persist($vetements);
            $manager->flush();

            return $this->redirectToRoute('vetement_list');
        }
        $types = $this->getDoctrine()->getRepository(Type::class)->findBy([],['libelle' => 'ASC']);

        return $this->render('vetement/add.html.twig', ['vetements' => $vetements, 'donnees' => $donnees, 'errors' => $errors, 'types' => $types]);
    }


    /**
     * @Route("/delete/vetement", name="vetement_delete", methods={"DELETE"})
     */
    public function delete(Request $request) {
        if(!$this->isCsrfTokenValid('vetement_delete', $request->get('token'))) {
            throw new  InvalidCsrfTokenException('Invalid CSRF token formulaire vetement');
        }

        $manager = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $vetement = $manager->getRepository(Vetement::class)->find($id);
        if(!$vetement) throw $this->createNotFoundException('No vetement dound for id' . $id);

        $this->addFlash('info_vetement', 'Vetement supprimé !');

        $manager->remove($vetement);
        $manager->flush();

        return $this->redirectToRoute('vetement_list');
    }

    /**
     * @Route("/edit/{id}/vetement", name="vetement_edit", methods={"GET"})
     * @Route("/edit/vetement", name="vetement_edit_valid", methods={"PUT"})
     */
    public function edit(Request $request, $id=null)
    {

        $manager = $this->getDoctrine()->getManager();
        $types = $this->getDoctrine()->getRepository(Type::class)->findBy([],['libelle' => 'ASC']);

        if($request->getMethod() == "GET") {
            $vetement = $manager->getRepository(Vetement::class)->find($id);
            return $this->render('vetement/edit.html.twig', ['vetement' => $vetement, 'types' => $types]);
        }

        if(!$this->isCsrfTokenValid('form_vetement', $request->get('token'))) {
            throw new  InvalidCsrfTokenException('Invalid CSRF token formulaire vetement');
        }

        $donnees['id'] = $request->request->get('id');
        $donnees['type_id'] = $request->request->get('type_id');
        $donnees['descriptif'] = $request->request->get('descriptif');
        $donnees['prixDeBase'] = $request->request->get('prixDeBase');
        $donnees['taille'] = $request->request->get('taille');
        $donnees['dateAchat'] = $request->request->get('dateAchat');

        $errors = $this->validator($donnees);

        if (empty($errors)) {
            $vetement = $manager->getRepository(Vetement::class)->find($donnees['id']);

            $vetement->setDescriptif($_POST['descriptif'])
                ->setPrixDeBase($_POST['prixDeBase'])
                ->setTaille($_POST['taille'])
                ->setDateAchat(\DateTime::createFromFormat('d/m/Y', $_POST['dateAchat']))
                ->setType($this->getDoctrine()->getRepository(Type::class)->find($donnees['type_id']));

            $this->addFlash('info_vetement', 'Vetement modifié !');

            $manager->persist($vetement);
            $manager->flush();

            return $this->redirectToRoute('vetement_list');
        }
        $types = $this->getDoctrine()->getRepository(Type::class)->findBy([],['libelle' => 'ASC']);
        $vetement = $manager->getRepository(Vetement::class)->find($donnees['id']);
        return $this->render('vetement/edit.html.twig', ['vetement' => $vetement, 'donnees' => $donnees, 'errors' => $errors, 'types' => $types]);
    }

    public function validator($donnees)
    {
        $errors = array();

        if (isset($donnees['id']) and !is_numeric($donnees['id'])) {
            $errors['id'] = 'type id incorect';
        }else{

        }

        if (!preg_match("/^[A-Za-z]{1,}/", $donnees['descriptif'])) {
            $errors['descriptif'] = 'ajouter un descriptif';
        }

        $dateConvert = DateTime::createFromFormat("d/m/Y", $donnees['dateAchat']);
        if ($dateConvert == NULL) {
            $errors['dateAchat'] = 'la date doit être au format JJ/MM/AAAA';
        } else {
            if($dateConvert->format('d/m/Y') !== $donnees['dateAchat']) {
                $errors['dateAchat'] = 'la date n\'est pas valide (format jj/mm/aaaa)';
            }
        }

        if (!is_numeric($donnees['prixDeBase'])) {
            $errors['prixDeBase'] = 'saisir une valeur numérique';
        }
        if (!is_numeric($donnees['taille'])) {
            $errors['taille'] = 'saisir une valeur numérique';
        }
        if (!is_numeric($donnees['type_id'])) {
            $errors['type_id'] = 'saisir une valeur';
        }else if($donnees['type_id']>0){
            $vetement['type_id']=$donnees['type_id'];
        }

        return $errors;
    }


}