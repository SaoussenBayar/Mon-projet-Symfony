Feature: Inscription d'un parent
  En tant que parent
  Je veux m'inscrire sur Babycare Hub
  Afin de pouvoir accéder aux services de la plateforme

  Scenario: Inscription réussie
    Given Je suis sur la page d'inscription
    When Je remplis le formulaire avec des informations valides
    And Je soumets le formulaire
    Then Je devrais voir un message de confirmation d'inscription

  Scenario: Inscription échouée avec un email déjà utilisé
    Given Je suis sur la page d'inscription
    When Je remplis le formulaire avec un email déjà utilisé
    And Je soumets le formulaire
    Then Je devrais voir un message d'erreur "Cet email est déjà pris"
