pipeline {
  agent any
  stages {
    stage('Verification') {
      steps {
        validateDeclarativePipeline 'Jenkinsfile'
        sh 'php -v'
        sh 'php -i'
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
            sh "yarn build"
        }
    }

    stage('PHP unit test & code coverage'){
        steps  {
            sh './vendor/bin/phpunit --coverage-clover ./reports/coverage.xml --log-junit ./reports/test.xml'
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

    stage('SetUp & scan') {
       steps {
         sh 'php --version'
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
            sh "ssh root@75.119.154.204 docker exec -it bcfd php7.4-fpm start"
            sh "ssh root@75.119.154.204 docker exec -it bcfd chmod 777 /var/run/php/php7.4-fpm.sock"
        }
    }

  }
}
