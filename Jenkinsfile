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
      parallel {
        stage('Build') {
          environment {
            DB_HOST = credentials('DB-Host')
            DB_USERNAME = credentials('DB_user')
            DB_PASSWORD = credentials('DB_PASS')
          }
          steps {
            sh 'php --version'
            sh 'composer install'
            sh 'composer --version'
            sh 'cp .env.example .env'
            sh 'echo DB_HOST=${DB_HOST} >> .env'
            sh 'echo DB_USERNAME=${DB_USERNAME} >> .env'
            sh 'echo DB_DATABASE=pre_BCFD >> .env'
            sh 'echo DB_PASSWORD=${DB_PASSWORD} >> .env'
            sh 'php artisan key:generate'
            sh 'cp .env .env.testing'
            sh 'php artisan migrate'
          }
        }

        stage('PHP test'){
            steps  {
                sh 'php artisan test'
            }
        }
      }
    }

    stage('Build & Push Docker container') {
        steps {
            sh "docker build -t bcfd_web ."
        }
        steps {
            sh "docker tag bcfd_web localhost:5000/bcfd_web"
            sh "docker push localhost:5000/bcfd_web"
        }

    }
  }
}
