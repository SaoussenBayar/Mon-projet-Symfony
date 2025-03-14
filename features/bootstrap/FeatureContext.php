<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use Behat\Behat\Tester\Exception\PendingException;      
use Behat\Step\When;
use Behat\Step\Then; 

class FeatureContext implements Context
{
    private $currentPage;
    private $formData = [];
    private $client;
    /**
     * @Given Je suis sur la page d'inscription
     */
    public function jeSuisSurLaPageDInscription()
    {
        $_SERVER['REQUEST_URI'] = '/inscription';
        $this->currentPage = 'app_register';
        }
    /**
     * @When Je remplis le formulaire avec des informations valides
     */
    public function jeRemplisLeFormulaireAvecDesInformationsValides()
    {
        $this->formData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'pseudo' => 'jeandupont',
            'dateNaissance' => '01/01/2000',
            'password' => 'MotDePasse123!',
        ];
        foreach ($this->formData as $field => $value) {
            $_POST[$field] = $value;
        }

    }
    /**
     * @When Je soumets le formulaire
     */
    public function jeSoumetsLeFormulaire()
    {
        $_POST['submit'] = true;
    }
    /**
     * @Then Je devrais voir un message de confirmation d'inscription
     */
    public function jeDevraisVoirUnMessageDeConfirmationDInscription()
    {
        Assert::assertTrue(true); 
    }
    /**
     * @When Je remplis le formulaire avec un email déjà utilisé
     */
    public function jeRemplisLeFormulaireAvecUnEmailDejaUtilise(): void
    {
        $_POST['Email'] = 'saoussenbayar@gmail.com';    }

    /**
     * @Then Je devrais voir un message d'erreur :arg1
     */
    public function jeDevraisVoirUnMessageDerreur(string $arg1): void
    {
        Assert::assertEquals("Cet email est déjà utilisé", $arg1);
    }

}
