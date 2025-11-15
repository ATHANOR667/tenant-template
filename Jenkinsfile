pipeline {
    agent any

    stages {
        stage('Build & Deploy') {
            steps {
                // Utiliser une image Docker contenant Docker/Docker Compose pour l'exécution
                // NOTE: Cette ligne nécessite que le Docker Socket de l'hôte soit monté sur l'agent Jenkins.
                docker.image('docker/compose:latest').inside {

                    // --- 1. Build Image ---
                    echo 'Demarrage de la construction de l’image Docker...'
                    sh 'docker build -t tenant-template .'

                    // --- 2. Deploy Stack ---
                    echo 'Demarrage du deploiement avec Docker Compose...'
                    sh 'docker-compose up -d'
                }
            }
        }
    }

    post {
        // Envoi de la notification en cas de SUCCES
        success {
            slackSend(
                channel: '#jenkins-notifications',
                color: 'good',
                message: "DEPLOIEMENT REUSSI : Job *${env.JOB_NAME}* (#${env.BUILD_NUMBER}) de l'application Laravel sur port *8089*."
            )
        }

        // Envoi de la notification en cas d'ECHEC
        failure {
            slackSend(
                channel: '#alertes-devops',
                color: 'danger',
                message: "ECHEC DU DEPLOIEMENT : Job *${env.JOB_NAME}* (#${env.BUILD_NUMBER}) a echoue. Consulter les logs ici : ${env.BUILD_URL}console"
            )
        }
    }
}
