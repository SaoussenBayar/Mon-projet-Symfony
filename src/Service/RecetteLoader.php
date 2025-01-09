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
            ["Purée de carottes", "Carottes, eau", "Cuire les carottes puis les mixer avec un peu d'eau.", 15, 6, "puree_carottes.jpg"],
            ["Compote de pommes", "Pommes, eau", "Cuire les pommes et les mixer en compote.", 20, 6, "compote_pommes.jpg"],
            ["Purée de courgettes", "Courgettes, eau", "Cuire les courgettes et les mixer.", 15, 6, "puree_courgettes.jpg"],
            ["Purée de patates douces", "Patates douces, eau", "Cuire les patates douces puis les mixer.", 20, 6, "puree_patates_douces.jpg"],
            ["Compote de poires", "Poires, eau", "Cuire les poires et les mixer.", 15, 6, "compotedepoire.jpg"],
            ["Purée de brocolis", "Brocolis, eau", "Cuire les brocolis et les mixer.", 20, 8, "puree_debrocolis.jpg"],
            ["Compote de bananes", "Bananes", "Écraser les bananes en purée.", 5, 6, "compoteBanane.jpg"],
            ["Purée de petits pois", "Petits pois, eau", "Cuire les petits pois et les mixer.", 20, 8, "puree_petitPois.jpg"],
            ["Purée de pommes de terre", "Pommes de terre, eau", "Cuire les pommes de terre et les mixer.", 20, 8, "puree_PommeDeTerre.jpg"],
            ["Compote de mangues", "Mangues", "Mixer la mangue jusqu'à obtenir une compote.", 10, 6, "compote_de_mangue.jpg"],
            ["Purée de panais", "Panais, eau", "Cuire les panais et les mixer.", 20, 6, "pureepanais.jpg"],
            ["Compote de fraises", "Fraises", "Mixer les fraises jusqu'à obtenir une compote.", 10, 8, "compotefraise.jpg"],
            ["Purée de betteraves", "Betteraves, eau", "Cuire les betteraves et les mixer.", 30, 10, "puree_betteraves.jpg"],
            ["Purée de haricots verts", "Haricots verts, eau", "Cuire les haricots verts et les mixer.", 20, 6, "Puree_haricots_verts.jpg"],
            ["Compote de kiwi", "Kiwi", "Mixer les kiwis jusqu'à obtenir une compote.", 5, 12, "compote_kiwi.jpg"],
            ["Purée de potiron", "Potiron, eau", "Cuire le potiron et le mixer.", 25, 6, "puree_potiron.jpg"],
            ["Compote d'abricots", "Abricots", "Mixer les abricots jusqu'à obtenir une compote.", 15, 8, "compote_abricots.webp"],
            ["Purée d'épinards", "Épinards, eau", "Cuire les épinards et les mixer.", 15, 8, "Puree_epinard.jpg"],
            ["Purée de fenouil", "Fenouil, eau", "Cuire le fenouil et le mixer.", 20, 6, "puree_fenouil.webp"],
            ["Compote de pêches", "Pêches", "Mixer les pêches jusqu'à obtenir une compote.", 10, 6, "compote_peche.webp"]
    
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
