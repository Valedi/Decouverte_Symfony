<?php


namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
//DefaultController hérite de AbstractController
//a faire systematiquement!!!

{
    /*
     * Page / action :Accueil
     */
    public function index()
    {
        return  $this->render('default/index.html.twig');
    }
    /*
     * Page / action :Contact
     */
    public function contact()
    {
        return  $this->render('default/contact.html.twig');
//        return new Response('<h1>Page Contact</h1>');//des que "new" taper respons..et faire entrer
    }
    /*
     * Page / action :Catégorie Politique sport etc....
     * Permet d'afficher les articles d'une catégorie avec l'alias ci-dessous sous forme de commentaire que php va interpréter
     *
     *
     * _________!!!!!!!!!!!!!!!!Quand on déclare une route, au minimum, on déclare les name et methods comme ci-dessous---
     *
     * mettre le @Route("/{alias}") ci-dessous!!!!!______________________________
     */



    /**
     * @Route("/{alias}", name="default_category", methods={"GET"})
     */
    public function category($alias)
    {
//        return new Response("<h1>Page $alias</h1>");
        return  $this->render('default/category.html.twig');
    }

    /*
     * Page / action :Article
     * Permet d'afficher un article du site
     *
     * _________!!!!!!!!!!!!mettre le @Route("/{alias} etc...") ci-dessous!!!!!________________________
     */


    /**
     * @Route("/{category}/{alias}_{id}.html", name="default_article",methods={"GET"})
     */
    public function post()
    {
      # URL :  https://localhost:8000/politique/couvre-feu-quand-la-situation-sanitaire-s-ameliorera-t-elle_14155614.html

//        return new Response("<h1>Page Article</h1>");
        return  $this->render('default/post.html.twig');

    }
}

