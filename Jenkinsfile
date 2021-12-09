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
        }
    }

  }
}
