<?php

namespace App\Service;

use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;

class RecetteLoader
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadRecettes()
    {
        $recettes = [
            ['Purée de carottes', 'Carottes, eau.', 'Éplucher les carottes, cuire à la vapeur, mixer.', 15, '4 à 6 mois', 'puree_carottes.jpg'],
            ['Compote de pommes', 'Pommes, eau.', 'Éplucher, cuire à feu doux, mixer.', 15, '4 à 6 mois', 'compote_pommes.jpg'],
            ['Purée de patates douces', 'Patates douces, eau.', 'Cuire les patates douces à la vapeur, mixer.', 20, '4 à 6 mois', 'puree_patates_douces.jpg'],
            ['Purée de courgettes', 'Courgettes, eau.', 'Laver, cuire, mixer.', 15, '4 à 6 mois', 'puree_courgettes.jpg'],
            ['Compote de poires', 'Poires, eau.', 'Éplucher, cuire à feu doux, mixer.', 15, '4 à 6 mois', 'compote_poires.jpg'],
    
            // Recettes pour 6 à 8 mois
            ['Purée de brocolis', 'Brocolis, eau.', 'Cuire les brocolis, mixer avec un peu d’eau.', 20, '6 à 8 mois', 'puree_brocolis.jpg'],
            ['Purée de haricots verts', 'Haricots verts, pommes de terre, eau.', 'Cuire, mixer.', 20, '6 à 8 mois', 'puree_haricots_verts.jpg'],
            ['Riz écrasé', 'Riz bien cuit, eau.', 'Cuire le riz, écraser avec un peu d’eau.', 30, '6 à 8 mois', 'riz_ecrase.jpg'],
            ['Purée de banane', 'Banane bien mûre.', 'Écraser la banane à la fourchette.', 5, '6 à 8 mois', 'puree_banane.jpg'],
            ['Purée de patates et épinards', 'Pommes de terre, épinards, eau.', 'Cuire, mixer.', 25, '6 à 8 mois', 'puree_patates_epinards.jpg'],
    
            // Recettes pour 9 à 12 mois
            ['Petit pot au poulet', 'Poulet, carottes, pommes de terre.', 'Cuire, mixer.', 25, '9 à 12 mois', 'pot_poulet.jpg'],
            ['Soupe de légumes variés', 'Pommes de terre, carottes, courgettes, eau.', 'Cuire, mixer.', 30, '9 à 12 mois', 'soupe_legumes_varies.jpg'],
            ['Purée de pois chiches', 'Pois chiches cuits, huile d’olive.', 'Mixer les pois chiches avec un filet d’huile.', 20, '9 à 12 mois', 'puree_pois_chiches.jpg'],
            ['Smoothie banane-fraise', 'Banane, fraises, yaourt.', 'Mixer les ingrédients.', 5, '9 à 12 mois', 'smoothie_banane_fraise.jpg'],
            ['Purée de chou-fleur', 'Chou-fleur, pommes de terre, eau.', 'Cuire et mixer.', 20, '9 à 12 mois', 'puree_chou_fleur.jpg'],
    
            // Recettes pour 12 mois et plus
            ['Omelette au fromage', 'Œuf, fromage râpé.', 'Battre les œufs avec le fromage, cuire à feu doux.', 10, '12 mois et +', 'omelette_fromage.jpg'],
            ['Pain perdu', 'Pain, lait, œuf.', 'Tremper le pain dans un mélange lait et œuf, cuire légèrement.', 15, '12 mois et +', 'pain_perdu.jpg'],
            ['Soupe de poulet et vermicelles', 'Poulet, carottes, vermicelles, eau.', 'Cuire à feu doux, mixer légèrement.', 30, '12 mois et +', 'soupe_poulet_vermicelles.jpg'],
            ['Mini-lasagnes', 'Pâtes à lasagnes, sauce tomate, viande hachée.', 'Préparer une mini-lasagne adaptée, cuire au four.', 40, '12 mois et +', 'mini_lasagnes.jpg'],
            ['Gâteau au yaourt', 'Yaourt, farine, œuf, sucre.', 'Mélanger, cuire au four.', 45, '12 mois et +', 'gateau_yaourt.jpg']
        
        ];

        foreach ($recettes as $data) {
            $recette = new Recette();
            $recette->setTitre($data[0]);
            $recette->setIngredients($data[1]);
            $recette->setDetail($data[2]);
            $recette->setTempsPrep($data[3]);
            $recette->setAgeRecommende($data[4]);
            $recette->setImage($data[5]);
            $this->entityManager->persist($recette);
        }

        $this->entityManager->flush();
    }
}
