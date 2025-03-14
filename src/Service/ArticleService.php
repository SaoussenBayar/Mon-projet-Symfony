<?php

namespace App\Service;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface; 

class ArticleService
{
    private EntityManagerInterface $em;
    private ParameterBagInterface $params;
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->em = $em;
        $this->params = $params;
    }

    public function edit(Article $article, Request $request): bool
    {
        if ($request->isMethod('POST')) { 
        $titre = $request->request->get('titre');
        if (!empty($titre)) {
            $article->setTitre($titre);
        }
        $description = $request->request->get('description');
        if (!empty($description)) {
            $article->setDescription($description);
        }
        $contenu = $request->request->get('contenu');
        if (!empty($contenu)) {
            $article->setContenu($contenu);
        }
             $uploadedFile = $request->files->get('image');
            if ($uploadedFile) {
                $uploadsDirectory = $this->params->get('kernel.project_dir') . '/public/uploads';
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                try {     
                    $uploadedFile->move($uploadsDirectory, $newFilename);
                    $article->setImage($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Failed to upload image.');
                }
            }
            $this->em->flush();
            return true; 
    }
    return false; 
}
}