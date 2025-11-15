pipeline {
    agent any

    stages {
        stage('Build Image') {
            steps {
                echo '🏗️ Démarrage de la construction de l’image Docker...'
                sh 'docker build -t tenant-template .'
            }
        }

        stage('Deploy Stack') {
            steps {
                echo '🚀 Démarrage du déploiement avec Docker Compose...'
                sh 'docker-compose up -d'
            }
        }
    }

    post {

        success {
            slackSend(
                channel: '#jenkins-notifications', /
                color: 'good',
                message: "✅ DEPLOIEMENT RÉUSSI : Job *${env.JOB_NAME}* (#${env.BUILD_NUMBER}) de l'application Laravel sur port *8089*."
            )
        }

        // Envoi de la notification en cas d'ÉCHEC
        failure {
            slackSend(
                channel: '#alertes-devops', // Vous pouvez choisir un canal d'alerte différent
                color: 'danger',
                message: "❌ ÉCHEC DU DEPLOIEMENT : Job *${env.JOB_NAME}* (#${env.BUILD_NUMBER}) a échoué. Consulter les logs ici : ${env.BUILD_URL}console"
            )
        }
    }
}
