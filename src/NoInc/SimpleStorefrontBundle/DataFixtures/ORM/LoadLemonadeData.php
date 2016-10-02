<?php

namespace NoInc\SimpleStorefrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NoInc\SimpleStorefrontBundle\Entity\User;
use NoInc\SimpleStorefrontBundle\Entity\Ingredient;
use NoInc\SimpleStorefrontBundle\Entity\Recipe;
use NoInc\SimpleStorefrontBundle\Entity\RecipeIngredient;

class LoadLemonadeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ingredients = [];
        // Insert ingredients
        foreach ( $this->ingredientArray() as $ingredientData )
        {
            $ingredient = new Ingredient();
            
            $ingredient->setName($ingredientData["name"]);
            $ingredient->setPrice($ingredientData["price"]);
            $ingredient->setMeasure($ingredientData["measure"]);
            $ingredient->setStock(100);
    
            $manager->persist($ingredient);
        
            $ingredients[$ingredient->getName()] = $ingredient;
        }
        $manager->flush();
        
        foreach( $this->recipeArray() as $recipeData)
        {
            $recipe = new Recipe();
            $recipe->setName($recipeData["name"]);
            $recipe->setPrice($recipeData["price"]);
            $manager->persist($recipe);
            $manager->flush();
            
            foreach( $recipeData["ingredients"] as $recipeIngredientData )
            {
                $recipeIngredient = new RecipeIngredient();
                
                $recipeIngredient->setIngredient($ingredients[$recipeIngredientData["name"]]);
                $recipeIngredient->setRecipe($recipe);
                $recipeIngredient->setQuantity($recipeIngredientData["quantity"]);
                $manager->persist($recipeIngredient);
            }
        }

        $manager->flush();
    }
    
    public function ingredientArray()
    {
        return [
            [
                "name" => "Lemon",
                "price" => 0.10,
                "measure" => "Juice"
            ],
            [
                "name" => "Sugar",
                "price" => 0.10,
                "measure" => "Cup"
            ],
            [
                "name" => "Water",
                "price" => 0.00,
                "measure" => "Cup"
            ],
            [
                "name" => "Orange",
                "price" => 0.10,
                "measure" => "Juice"
            ],
            [
                "name" => "Whiskey",
                "price" => 0.10,
                "measure" => "fl oz"
            ],
            [
                "name" => "Vodka",
                "price" => 1,
                "measure" => "fl oz"
            ],
            [
                "name" => "Rum",
                "price" => 1,
                "measure" => "fl oz"
            ],
            [
                "name" => "Mint Leaf",
                "price" => .25,
                "measure" => "leaves"
            ],
            [
                "name" => "Coke",
                "price" => .10,
                "measure" => "fl oz"
            ]
        ];
    }
    
    public function recipeArray()
    {
        return [
            [
                "name" => "Lemonade",
                "price" => 1.00,
                "ingredients" => [
                    [
                        "name" => "Lemon",
                        "quantity" => 2
                    ],
                    [
                        "name" => "Sugar",
                        "quantity" => 0.5
                    ],
                    [
                        "name" => "Water",
                        "quantity" => 4
                    ],
                ]
            ],
            [   
                "name" => "Rum and Coke",
                "price" => 5.00,
                "ingredients" => [
                    [
                        "name" => "Rum",
                        "quantity" => 1.5
                    ],
                    [
                        "name" => "Coke",
                        "quantity" => 5
                    ],
                    [
                        "name" => "Mint Leaf",
                        "quantity" => 1
                    ],
                ]
            ],
            [   
                "name" => "Mojito",
                "price" => 5.00,
                "ingredients" => [
                    [
                        "name" => "Water",
                        "quantity" => 1.5
                    ],
                    [
                        "name" => "Rum",
                        "quantity" => 2
                    ],
                    [
                        "name" => "Mint Leaf",
                        "quantity" => 6
                    ],
                    [
                        "name" => "Sugar",
                        "quantity" => 4
                    ]
                ]
            ]
        ];
    }
    
    public function getOrder()
    {
        return 2;
    }
}
