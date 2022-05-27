<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client', methods:'GET')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
    
    //=====================================================store.........
    #[Route('/client', name: 'app_store', methods:'POST')]
    public function createProduct(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $client = new Client();
        $client->setNom($request->request->get('nom'));
        $client->setPrenom($request->request->get('prenom'));
        $client->setAge($request->request->get('age'));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($client);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        //return new Response('Saved new client with id '.$client->getId());
        return $this->redirectToRoute('app_affiche');
    }


    //=====================================================list of all elements.........
    
    #[Route('/client/affiche', name: 'app_affiche', methods:'GET')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Client::class);
        $clients = $repository->findAll();


        if (!$clients) {
            throw $this->createNotFoundException(
                'Pas de client '
            );
        }

        //return new Response('Check out this great product: '.$product->getName());
        //return dd($clients);
        $num = 1;

        return $this->render('client/affiche.html.twig',['clients' => $clients, 'num' => $num]);
    }


        //=====================================================list of all elements.........
    
        #[Route('/client/show{id}', name: 'app_show', methods:'GET')]
        public function show(ManagerRegistry $doctrine, int $id): Response
        {
            $repository = $doctrine->getRepository(Client::class);
            $client = $repository->find($id);
    
    
            if (!$client) {
                throw $this->createNotFoundException(
                    'Pas de client '
                );
            }
    
            $num = 1;
    
            return $this->render('client/show.html.twig',['client' => $client]);
        }
    
    



    //=====================================================Remove elelemt.........
    #[Route('/client/delete/{id}', name:'app_delete', methods:'GET')]
    public function delete (ManagerRegistry $doctrine, int $id){

        $entityManager = $doctrine->getManager();

        $repository = $doctrine->getRepository(Client::class);
        $client = $repository->find($id);

        $entityManager->remove($client);
        $entityManager->flush();


        return $this->redirectToRoute('app_affiche');
    }

        //=====================================================Edit elelemt.........
        #[Route('/client/edit/{id}', name:'app_edit', methods:'GET')]
        public function edit (ManagerRegistry $doctrine, int $id){
    
            $repository = $doctrine->getRepository(Client::class);
            $client = $repository->find($id);

            return $this->render('/client/edit.html.twig', compact('client'));
        }


        //=====================================================update.........
    #[Route('/client/update{id}', name: 'app_update', methods:'POST')]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Client::class);
        $client = $repository->find($id);

        
        $client->setNom($request->request->get('nom'));
        $client->setPrenom($request->request->get('prenom'));
        $client->setAge($request->request->get('age'));

        $entityManager->persist($client);

        $entityManager->flush();

        return $this->redirectToRoute('app_affiche');
    }

}


