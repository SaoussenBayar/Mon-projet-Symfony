pipeline {
    agent any

    environment {
        IMAGE_NAME = "mon-app-symfony"
        CONTAINER_NAME = "mon-app-container"
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com//SaoussenBayar/Jenkins.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t $IMAGE_NAME .'
            }
        }

        stage('Run Tests') {
            steps {
                sh 'docker run --rm $IMAGE_NAME php bin/phpunit'
            }
        }

        stage('Stop Old Container') {
            steps {
                script {
                    sh "docker stop $CONTAINER_NAME || true"
                    sh "docker rm $CONTAINER_NAME || true"
                }
            }
        }

        stage('Run New Container') {
            steps {
                sh "docker run -d --name $CONTAINER_NAME -p 8080:80 $IMAGE_NAME"
            }
        }
    }

    post {
        success {
            echo "Déploiement réussi !"
        }
        failure {
            echo "Échec du déploiement !"
        }
    }
}
