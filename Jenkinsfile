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

    stage('Write .env [testing]') {
        steps{
            withCredentials([file(credentialsId: 'lscofd-Test', variable: 'envfile')]) {
                writeFile file: '.env.testing', text: readFile(envfile)
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
            sh "docker build -t lscofd_web ."
            sh "docker tag lscofd_web simonloudev/lscofd_web"
            sh "docker push simonloudev/lscofd_web"
            sh "cat lscofd-docker-compose.yml | ssh root@75.119.154.204 'cat - > /infra/web/lscofd/docker-compose.yml'"
            sh "cat sams-docker-compose.yml | ssh root@75.119.154.204 'cat - > /infra/web/sams/docker-compose.yml'"
        }
    }

    stage('Launch LSCoFD'){
        steps{
            sh "rm .env.testing"
            withCredentials([file(credentialsId: 'lscofd-Prod', variable: 'envfile')]) {
                 writeFile file: '.env', text: readFile(envfile)
            }
            sh "ssh root@75.119.154.204 docker-compose -f /infra/web/lscofd/docker-compose.yml down"
            sh "ssh root@75.119.154.204 docker-compose -f /infra/web/lscofd/docker-compose.yml up -d"
            sh "ssh root@75.119.154.204 docker exec -i LSCoFD service php7.4-fpm start"
            sh "ssh root@75.119.154.204 docker exec -i LSCoFD chmod 777 /var/run/php/php7.4-fpm.sock"
            sh "ssh root@75.119.154.204 docker exec -i LSCoFD chmod 777 -R /usr/share/nginx/lscofd/"
            sh "ssh root@75.119.154.204 docker exec -i LSCoFD chown www-data -R /usr/share/nginx/lscofd/"
            sh "ssh root@75.119.154.204 docker exec -i LSCoFD pm2 start queueworker.yml"
            sh "ssh root@75.119.154.204 docker exec -i LSCoFD php artisan storage:link"
            sh "cat .env | ssh root@75.119.154.204 'cat - > /infra/web/lscofd/.env'"
            sh "ssh root@75.119.154.204 docker cp /infra/web/lscofd/.env LSCoFD:/usr/share/nginx/lscofd/.env"
            sh "ssh root@75.119.154.204 rm /infra/web/lscofd/.env"
        }
    }

    stage('Launch SAMS'){
            steps{
                withCredentials([file(credentialsId: 'sams-Prod', variable: 'envfile')]) {
                     writeFile file: '.env', text: readFile(envfile)
                }
                sh "ssh root@75.119.154.204 docker-compose -f /infra/web/sams/docker-compose.yml down"
                sh "ssh root@75.119.154.204 docker-compose -f /infra/web/sams/docker-compose.yml up -d"
                sh "ssh root@75.119.154.204 docker exec -i SAMS service php7.4-fpm start"
                sh "ssh root@75.119.154.204 docker exec -i SAMS chmod 777 /var/run/php/php7.4-fpm.sock"
                sh "ssh root@75.119.154.204 docker exec -i SAMS chmod 777 -R /usr/share/nginx/lscofd/"
                sh "ssh root@75.119.154.204 docker exec -i SAMS chown www-data -R /usr/share/nginx/lscofd/"
                sh "ssh root@75.119.154.204 docker exec -i SAMS pm2 start queueworker.yml"
                sh "ssh root@75.119.154.204 docker exec -i SAMS php artisan storage:link"
                sh "cat .env | ssh root@75.119.154.204 'cat - > /infra/web/sams/.env'"
                sh "ssh root@75.119.154.204 docker cp /infra/web/sams/.env LSCoFD:/usr/share/nginx/lscofd/.env"
                sh "ssh root@75.119.154.204 rm /infra/web/sams/.env"
            }
        }

  }
}
