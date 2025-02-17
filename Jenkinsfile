pipeline {
    agent any  // On n'affecte pas un agent global ici, mais on le définit dans chaque stage

    environment {
        // Définition des variables d'environnement
        COMPOSER_HOME = "${WORKSPACE}/composer"
        DATABASE_URL = "mysql://$MYSQL_USER:$MYSQL_PASSWORD@mysql:3306/$MYSQL_DATABASE"
    }

    stages {
        stage('Checkout') {
            steps {
                // Récupère le code depuis le dépôt Git
                git branch: 'main', url: 'https://github.com/SaoussenBayar/Mon-projet-Symfony.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                // Construction de l'image Docker
                sh 'docker build -t babycare-hub .'
            }
        }

        stage('Stop Old Container') {
            steps {
                // Arrêt et suppression de l'ancien conteneur Docker
                sh 'docker stop babycare-hub || true && docker rm babycare-hub || true'
            }
        }

        stage('Run New Container') {
            steps {
                // Lancement du nouveau conteneur Docker
                sh 'docker run -d --name babycare-hub -p 8080:80 babycare-hub'
            }
        }

        stage('Install Dependencies') {
            steps {
                // Installation des dépendances PHP via Composer
                sh 'docker exec babycare-hub composer install --no-interaction'
            }
        }

        stage('Run Unit Tests with PHPUnit') {
            steps {
                // Exécution des tests unitaires avec PHPUnit dans le conteneur
                sh 'docker exec babycare-hub vendor/bin/phpunit --configuration phpunit.xml.dist --coverage-html=build/coverage'
            }
        }

        stage('Run Functional Tests with Symfony Panther') {
            steps {
                // Exécution des tests fonctionnels avec Symfony Panther
                sh 'docker exec babycare-hub bin/phpunit --testdox --coverage-html=build/functional_coverage'
            }
        }

        stage('Publish Test Results') {
            steps {
                // Publication des résultats des tests (ex. PHPUnit)
                junit '**/build/test-*.xml'
                // Publication des rapports de couverture de tests
                publishHTML(target: [
                    reportName: 'PHPUnit Test Coverage',
                    reportDir: 'build/coverage',
                    reportFiles: 'index.html',
                    keepAll: true
                ])
                publishHTML(target: [
                    reportName: 'Functional Test Coverage',
                    reportDir: 'build/functional_coverage',
                    reportFiles: 'index.html',
                    keepAll: true
                ])
            }
        }
    }

    post {
        always {
            // Nettoyage après exécution du pipeline
            cleanWs()  // Nettoie l'espace de travail
        }
        success {
            // Notification en cas de succès
            echo 'Le pipeline a réussi, tout est bon !'
        }
        failure {
            // Notification en cas d'échec
            emailext (
                subject: 'Échec des tests dans Babycare Hub',
                body: 'Le pipeline d\'intégration continue a échoué. Consultez les logs pour plus de détails.',
                to: 'developer@domaine.com'
            )
        }
    }
}
