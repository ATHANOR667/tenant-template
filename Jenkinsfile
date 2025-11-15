pipeline {
    agent any

    stages {
        stage('Check Docker Access') {
            steps {
                echo 'Verification de l’accessibilite de Docker...'
                sh 'docker info' // Commande pour vérifier si Docker est joignable
            }
        }

        stage('Build Image') {
            steps {
                echo 'Demarrage de la construction de l’image Docker...'
                sh 'docker build -t tenant-template .'
            }
        }

        stage('Deploy Stack') {
            steps {
                echo 'Demarrage du deploiement avec Docker Compose...'
                sh 'docker-compose up -d'
            }
        }
    }

    post {
        // Envoi de la notification en cas de SUCCÈS
        success {
            slackSend(
                channel: '#jenkins-notifications',
                color: 'good',
                message: "DEPLOIEMENT RÉUSSI : Job *${env.JOB_NAME}* (#${env.BUILD_NUMBER}) de l'application Laravel sur port *8089*."
            )
        }

        // Envoi de la notification en cas d'ÉCHEC
        failure {
            slackSend(
                // Assurez-vous que ce canal existe et que le Bot y est invité
                channel: '#alertes-devops',
                color: 'danger',
                message: "ÉCHEC DU DEPLOIEMENT : Job *${env.JOB_NAME}* (#${env.BUILD_NUMBER}) a échoué. Consulter les logs ici : ${env.BUILD_URL}console"
            )
        }
    }
}
