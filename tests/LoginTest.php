<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testLoginWithValidCredentials()
    {
        $client = static::createClient();

        // Simuler un envoi de formulaire de connexion
        $crawler = $client->request('GET', '/login'); // URL de la page de connexion

        // Vérifier que la page de connexion s'affiche correctement
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec des données valides (exemple : un utilisateur existant)
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Saoussenbayar@gmail.com', // Utilisateur existant
            '_password' => '123456789',       // Le mot de passe correspondant
        ]);

        // Envoi du formulaire
        $client->submit($form);

        // Vérifier que l'utilisateur est redirigé après une connexion réussie (par exemple vers la page d'accueil)
        $this->assertResponseRedirects('app_home'); // Remplacer '/home' par l'URL vers laquelle l'utilisateur est redirigé après une connexion réussie

        // Suivre la redirection pour vérifier que la page d'accueil est affichée après la connexion
        $client->followRedirect();

    }

}

