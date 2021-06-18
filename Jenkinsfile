pipeline {
  agent any
  stages {
    stage('Verification') {
      steps {
        validateDeclarativePipeline 'Jenkinsfile'
        sh 'php -v'
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

        stage('Scan  SonarQube') {
          environment {
            scannerHome = 'sonar'
          }
          steps {
            withSonarQubeEnv(installationName: 'Serveur sonarqube', credentialsId: 'sonarqube_access_token') {
              echo 'coucou'
            }

          }
        }

      }
    }

    stage('Unit test') {
      steps {
        sh 'php artisan test'
      }
    }

    stage('Reponse Sonarqube analyst') {
      parallel {
        stage('Reponse Sonarqube analyst') {
          steps {
            waitForQualityGate(abortPipeline: true, webhookSecretId: 'sonarsecret_webhook', credentialsId: 'sonarqube_access_token')
          }
        }

        stage('modify files for prod') {
          steps {
            echo 'test'
          }
        }

      }
    }

    stage('Push on prod') {
      steps {
        echo 'git add commit and push'
        git(url: 'https://github.com/SimonLou-Dev/BCFD', branch: 'prod', changelog: true, credentialsId: 'github')
      }
    }

    stage('Clean') {
      steps {
        echo 'test'
      }
    }

  }
}