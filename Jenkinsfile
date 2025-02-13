pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/SaoussenBayar/Mon-projet-Symfony.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t babycare-hub .'
            }
        }

        stage('Stop Old Container') {
            steps {
                sh 'docker stop babycare-hub || true && docker rm babycare-hub || true'
            }
        }

        stage('Run New Container') {
            steps {
                sh 'docker run -d --name babycare-hub -p 8080:80 babycare-hub'
            }
        }
    }
}
