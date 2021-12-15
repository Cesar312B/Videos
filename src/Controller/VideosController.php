<?php

namespace App\Controller;

use App\Entity\Videos;
use App\Form\VideosType;
use App\Repository\VideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/")
 */
class VideosController extends AbstractController
{
    /**
     * @Route("/", name="videos_index", methods={"GET","POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(VideosRepository $videosRepository,PaginatorInterface $paginator,Request $request): Response
    {
     
        {

         $query= $videosRepository->findAll();
          $appointments = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $query,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            6
        ); 
        
        return $this->render('videos/index.html.twig', [
      
            'videos' => $appointments,
        ]);

        }
     
        
       
    }


       /**
     * @Route("/search", name="videos_search", methods={"GET","POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function search(VideosRepository $videosRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $form =$this->createFormBuilder(['method' => Request::METHOD_GET])
        ->add('Search',TextType::class,[
            'label'=> 'Buscar',
            'constraints' => [
                new NotBlank([
                    'message' => 'Escriba algo',
                ]),
             
            ],
        ])
      
        ->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
             $data= $form->getData();
             $appointments= $videosRepository->findBySearch($data['Search']);
           
    
             return $this->render('videos/search_index.html.twig', [
                'form' => $form->createView(),
                'videos' => $appointments,
            ]);
        

         
        }

        return $this->render('videos/atv.html.twig', [
            'form' => $form->createView(),
            
        ]);
        
       
    }
    

    /**
     * @Route("/admin", name="videos_admin", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin(VideosRepository $videosRepository): Response
    {
    
        return $this->render('videos/admin.html.twig', [
            'videos' => $videosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="videos_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $video = new Videos();
        $form = $this->createForm(VideosType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('file')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
            $video->setFile($newFilename);
     
            }

            $brochureFile2 = $form->get('Caratula')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile2) {
                $originalFilename = pathinfo($brochureFile2->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile2->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile2->move(
                        $this->getParameter('brochures_caratula'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $video->setCaratula($newFilename);
            }

            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirectToRoute('videos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('videos/new.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="videos_show", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function show(Videos $video): Response
    {
        return $this->render('videos/show.html.twig', [
            'video' => $video,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="videos_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Videos $video, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(VideosType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('file')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                     // instead of its contents
            $video->setFile($newFilename);

         
            }


            $brochureFile2 = $form->get('Caratula')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile2) {
                $originalFilename = pathinfo($brochureFile2->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile2->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile2->move(
                        $this->getParameter('brochures_caratula'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $video->setCaratula($newFilename);

         
            }

            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Tus cambios se han guardado!'
            );
            return $this->redirect($request->getUri());
        }

        return $this->renderForm('videos/edit.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="videos_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Videos $video, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $entityManager->remove($video);
            $entityManager->flush();
        }

        return $this->redirectToRoute('videos_index', [], Response::HTTP_SEE_OTHER);
    }
}
