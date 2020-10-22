<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{


    /**
     * Formulaire permettant de créer un article
     * @Route("/article/creer-un-article", name="post_create", methods={"GET|POST"})
     */
    public function createPost(Request $request, SluggerInterface $slugger)
    {
        #1a. Création d'un nouveau Post c'est a dire article si category, ou user, changer
        $post = new Post();
            #1b. Attribution d'un user
            # FIXME Temporaire
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(1);

        $post->setUser($user);

             #1c. Ajout de la date de création
        $post->setCreatedAt(new \DateTime());

        #2 Création d'un formulaire avec $post
        $form = $this->createFormBuilder($post)

            #2a Titre de l'article (on doit utiliser les protiété de notre entité
            ->add('title', TextType::class)

            #2b. Categorie de l'article (liste déroulante)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])

            #2c. Contenu de l'article
            ->add('content', TextareaType::class)

            #2d. Upload Image de l'article
            ->add('featuredImage', FileType::class)

            #2e. Boutton submit de l'article
            ->add('submit', SubmitType::class)
            ->getForm();

        #3. Demande à Symfonie de récupérer les infos de la request.
        $form->handleRequest($request);

        #4. Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

//        dump($request);
//        dd($post);

            #4a. TODO Gestion Upload de l'image
            #4b. Génération de l'alias
            $post->setAlias(
                $slugger->slug(
                    $post->getTitle()
                )
            );
            #4c. Sauvegarde de la BDD (tjs le smême 3 lignes
            /*
             * qu'est ce que le Entity manager (em)
             * C'est une classe qui sait comment sauvegrader d'autres classes
             */
            $em = $this->getDoctrine()->getManager(); #Récupération de EM
            $em->persist($post); # Demande pour dauvegarder en BDD $post
            $em->flush();


            #4d. Notification /Confirmation
            $this->addFlash('notice', 'Votre article est en ligne!');

            #4e. Redirection
            return $this->redirectToRoute('default_article',[
                'category'=>$post->getCategory()->getAlias(),
                'alias'=>$post->getAlias(),
                'id'=>$post->getId()
            ]);
    }

        #(5. Transmission du formulaire à la vue
        return $this->render('post/create.html.twig',
            [
                'form' => $form->createView()
            ]);


    }

}