pipeline {
  agent any
  stages {
    stage('Verification') {
      steps {
        validateDeclarativePipeline 'Jenkinsfile'
        sh 'php -v'
        sh 'php -i'
        sh "rm .env"
      }
    }

    stage('Write .env') {
        steps{
            withCredentials([file(credentialsId: 'BCFD-Infra', variable: 'envfile')]) {
                writeFile file: '.env', text: readFile(envfile)
            }
        }
    }

    stage('Setup project') {
        steps{
            sh "composer install"
            sh "yarn install"
            sh "yarn build"
        }
    }

    stage('Scan  SonarQube') {
        environment {
            scannerHome = tool 'sonar'
        }
        steps {
            withSonarQubeEnv(installationName: 'Le miens', credentialsId: 'Sonarqube - Token') {
                sh '${scannerHome}/bin/sonar-scanner'
            }
        }
    }

    stage('Build & Push Docker container') {
        steps {
            sh "docker build -t bcfd_web ."
            sh "docker tag bcfd_web simonloudev/bcfd_web"
            sh "docker push simonloudev/bcfd_web"
            sh "cat docker-compose.yml | ssh root@75.119.154.204 'cat - > /infra/web/bcfd/docker-compose.yml'"
        }
    }

    stage('Launch'){
        steps{
            sh "ssh root@75.119.154.204 docker-compose -f /infra/web/bcfd/docker-compose.yml down"
            sh "ssh root@75.119.154.204 docker-compose -f /infra/web/bcfd/docker-compose.yml up -d"
            sh "ssh root@75.119.154.204 docker exec -i bcfd service php7.4-fpm start"
            sh "ssh root@75.119.154.204 docker exec -i bcfd chmod 777 /var/run/php/php7.4-fpm.sock"
            sh "ssh root@75.119.154.204 docker exec -i bcfd chmod 777 -R /usr/share/nginx/bcfd/"
            sh "ssh root@75.119.154.204 docker exec -i bcfd chown www-data -R /usr/share/nginx/bcfd/"
            sh "ssh root@75.119.154.204 docker exec -i bcfd pm2 start queueworker.yml"
            sh "ssh root@75.119.154.204 docker exec -i bcfd php artisan storage:link"
            sh "ssh root@75.119.154.204 docker cp .env bcfd:/usr/share/nginx/bcfd/.env"
        }
    }

  }
}
