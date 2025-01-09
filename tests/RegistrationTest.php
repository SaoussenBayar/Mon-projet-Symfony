<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    public function testSuccessfulRegistration()
    {
        $client = static::createClient();

        // Simuler un envoi de formulaire d'inscription
        $crawler = $client->request('GET', '/inscription'); // URL de la page d'inscription

        // Vérifier que la page de l'inscription est affichée
        $this->assertResponseIsSuccessful();

        // Soumettre le formulaire avec des données valides
        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@example.com',
            'registration_form[nom]' => 'newuser',
            'registration_form[prenom]' => 'newuser',
            'registration_form[dateNaissance]' => '2000-01-01',
            'registration_form[ville]' => 'New York',
            'registration_form[pays]' => 'USA',
            'registration_form[password]' => 'Valide@123',
        ]);

        // Envoi du formulaire
        $client->submit($form);

        // Vérifier que l'utilisateur est redirigé vers la page d'accueil ou la page de confirmation après une inscription réussie
        $this->assertResponseRedirects('app_home'); // Remplacer par la route de redirection après inscription réussie
    }
}
