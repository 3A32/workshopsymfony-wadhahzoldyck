<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassType;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }



    #[Route('/classroomList', name: 'app_classrooms')]
    public function list(ClassroomRepository $repository)
    {
        $Classroom = $repository->findAll();
        return $this->render('classroom/list.html.twig', array('tabClassroom' => $Classroom));
    }

    #[Route('/addClass', name: 'app_addClassrooms')]
    public function addClass(ManagerRegistry $doctrine, Request $request)
    {
        $class = new Classroom();
        $form = $this->createForm(ClassType::class, $class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($class);
            $em->flush();
            return $this->redirectToRoute("app_classrooms");
        }
        return $this->renderForm("classroom/addClass.html.twig", array("formClass" => $form));
    }

    #[Route('/updateClass/{id}', name: 'app_updateClassrooms')]
    public function updateClass(ManagerRegistry $doctrine, Request $request, ClassroomRepository $repository, $id)
    {
        $class = $repository->find($id);
        $form = $this->createForm(ClassType::class, $class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_classrooms");
        }
        return $this->renderForm("classroom/addClass.html.twig", array("formClass" => $form));
    }

    #[Route('/deleteClass{id}', name: 'app_deleteClassrooms')]
    public function deleteClass(ManagerRegistry $doctrine, ClassroomRepository $repository, $id)
    {
        $class = $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($class);
        $em->flush();
        return $this->redirectToRoute("app_classrooms");
    }
}
