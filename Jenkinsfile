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
            scannerHome = tool 'sonar'
          }
          steps {
            withSonarQubeEnv(installationName: 'Serveur sonarqube', credentialsId: 'sonarqube_access_token') {
              //sh '${scannerHome}/bin/sonar-scanner'
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

    stage('Pre-Deploy') {
      parallel {
        stage('Reponse Sonarqube analyst') {
          steps {
            echo 'coucou'
            //waitForQualityGate(credentialsId: 'sonarqube_access_token', webhookSecretId: 'sonarsecret_webhook', abortPipeline: false)
          }
        }


        stage('Set Maintenance to the MainSite') {
          steps {
            echo 'coucou'
            sshagent (credentials: ['myserver']) {
                sh 'ssh -o StrictHostKeyChecking=no -l root'
            }
            sh 'ssh root@75.119.154.204 -o StrictHostKeyChecking=no'
            sh 'ls -l'
          }
        }

        stage('Prepare GitHub') {
          steps {
            echo 'change gitigniore for add Vite config'
          }
        }

      }
    }

    stage('Push on prod') {
      steps {
        echo 'git add commit and push'
      }
    }

    stage('Deploying on Prod') {
      steps {
        echo 'git pull tu connais'
      }
    }

    stage('Mise en Prod') {
      parallel {
        stage('Front Build') {
          steps {
            echo 'yarn build and cache clear'
          }
        }

        stage('db migrate') {
          steps {
            echo 'migrate DB'
          }
        }

      }
    }

    stage('Clean') {
      steps {
        echo 'reset DB'
      }
    }

  }
}
