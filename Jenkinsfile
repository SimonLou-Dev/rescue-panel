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

    stage('SetUp & scan') {
       steps {
         sh 'php --version'
       }
    }



    stage('Build & Push Docker container') {
       steps {
            sh "docker build -t bcfd_web ."
            sh "docker tag bcfd_web localhost:5000/bcfd_web"
            sh "docker push localhost:5000/bcfd_web"
      }
    }

    stage('Prepare to launch'){
        steps{
            sh "cat docker-compose.yml | ssh root@75.119.154.204 'cat - > /infra/web/bcfd/docker-compose.yml'"
            sh " result=$( ssh root@75.119.154.204 docker ps -q -f name=localhost:5000/bcfd_web )"
            sh " if [[ -n "$result" ]]; then ssh root@75.119.154.204 docker-compose -f /infra/web/bcfd/docker-compose.yml down; fi"
        }
    }
  }
  stage('Launch'){
       steps{
           sh "ssh root@75.119.154.204 docker-compose -f /infra/web/bcfd/docker-compose.yml up -d"
       }
  }
}
