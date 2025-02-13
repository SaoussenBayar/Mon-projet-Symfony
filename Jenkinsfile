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
                sh 'docker build -t DEFI .'
            }
        }

        stage('Stop Old Container') {
            steps {
                sh 'docker stop DEFI || true && docker rm DEFI || true'
            }
        }

        stage('Run New Container') {
            steps {
                sh 'docker run -d --name DEFI -p 8080:80 DEFI'
            }
        }
    }
}
