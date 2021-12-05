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
       stage('Build') {
         steps {
           sh 'php --version'
           sh 'composer install'
         }
       }
    }

     stage('PHP test'){
         steps  {
             sh 'php artisan test'
         }
     }

    stage('Build & Push Docker container') {
       steps {
                   sh "docker build -t bcfd_web ."
                   sh "docker tag bcfd_web localhost:5000/bcfd_web"
                   sh "docker push localhost:5000/bcfd_web"
      }
    }
  }
}
