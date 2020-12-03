<?php

namespace App\Controller;

use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    /**
     * @Route("/type/list", name="type_list",methods={"GET"})
     */
    public function list()
    {
        $types = $this->getDoctrine()->getRepository(Type::class)->findBy([],['id' => 'ASC']);
        return $this->render('type/list.html.twig', ['types'=>$types]);
    }

    /**
     * @Route("/type/showdetails", name="type_showdetails", methods={"GET"})
     */
    public function detailsProduit()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $types=$entityManager->getRepository("App:Vetement")->getDetailsVetements();
        dump($types);
        return $this->render('type/showdetails.html.twig', ['types' => $types]);
    }
}