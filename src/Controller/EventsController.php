<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EventsType;
use App\Entity\Events;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;



class EventsController extends AbstractController
{
    #[Route('/', name: 'app_events')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(events::class)->findAll();
        return $this->render('events/index.html.twig', [
            "events" => $events
        ]);
    }

    #[Route('/create', name: 'create_events')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $Events = new Events();
        $form = $this->createForm(EventsType::class, $Events);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $Events = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($Events);
            $em->flush();

            

            return $this->redirectToRoute('app_events');
        }

        return $this->render('events/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_events')]
    public function edit($id, Request $request, ManagerRegistry $doctrine): Response
    {
        $Events = $doctrine->getRepository(Events::class)->find($id);
        $form = $this->createForm(EventsType::class, $Events);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $Events = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($Events);
            $em->flush();

            

            return $this->redirectToRoute('app_events');
        }

        return $this->render('events/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/details/{id}', name: 'details_events')]
    public function details($id, ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Events::class)->find($id);
        return $this->render('events/details.html.twig', [
            "events" => $events
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_events')]
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Events::class)->find($id);
        $em = $doctrine->getManager();

        $em->remove($events);
        $em->flush();
      
        return $this->redirectToRoute("app_events");
    }

    
    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, EventsRepository $EventsRepository)
    {
        $Events = new Events();
        $form = $this->createForm(EventsType::class, $Events);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            $EventsRepository->save($Events, true);

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                  
                }

                $Events->setimage($newFilename);
            }

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('/image/new.html.twig', [
            'form' => $form,
        ]);
    }

}
