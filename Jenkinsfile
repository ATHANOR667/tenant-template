pipeline {
    agent any

    stages {
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
