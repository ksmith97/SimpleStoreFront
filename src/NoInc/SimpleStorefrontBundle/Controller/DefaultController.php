<?php

namespace NoInc\SimpleStorefrontBundle\Controller;

use NoInc\SimpleStorefrontBundle\Entity\Recipe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="guest_home")
     * @Method("GET")
     */
    public function getAction()
    {
        $recipes = $this->getDoctrine()->getRepository('NoIncSimpleStorefrontBundle:Recipe')->getRecipesAndIngredients();
        
        $renderData = [];
        
        $renderData['title'] = 'A Simple Storefront';
        $renderData['recipes'] = $recipes;
            
        return $this->render('NoIncSimpleStorefrontBundle:Default:index.html.twig', $renderData);
    }

    /**
     * @Route("/recipes", name="get_recipes")
     * @Method("GET")
     */
    public function getRecipes()
    {
        $recipes = $this->getDoctrine()->getRepository('NoIncSimpleStorefrontBundle:Recipe')->getRecipesAndIngredients();
        $response = new JsonResponse();
        $response->setData(array(
            'recipes' => $recipes
        ));
        return $response;
    }
    
    /**
     * @Route("/buy/{recipe_id}", name="buy_product")
     * @Method("POST")
     * @ParamConverter("recipe", class="NoIncSimpleStorefrontBundle:Recipe", options={"mapping": {"recipe_id": "id"}})
     */
    public function postBuyProductAction(Recipe $recipe)
    {
        if ( $recipe->getProducts()->count() > 0 )
        {
            $product = $recipe->getProducts()->first();
            $this->getDoctrine()->getEntityManager()->remove($product);
            $this->getDoctrine()->getEntityManager()->flush();
        }
        
        return $this->redirectToRoute('guest_home');
    }
    
    
}
