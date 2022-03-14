pipeline {
  agent any
  options {
    skipDefaultCheckout(true)
  }
  environment {
        SENTRY_AUTH_TOKEN = credentials('sentry-auth-token')
        SENTRY_ORG = 'simonlou'
        SENTRY_ENVIRONMENT = 'production'
        SENTRY_RELEASE= '3.0.2'
        SENTRY_URL='https://sentry.simon-lou.com/'
    }

  stages {
        stage('Cleaning'){
        steps{
            cleanWs()
            checkout scm
        }
    }

    stage('Verification') {
      steps {
        validateDeclarativePipeline 'Jenkinsfile'
        sh 'php -v'
        sh 'php -i'

      }
    }



    stage('Write .env [prod]') {
        steps{

            withCredentials([file(credentialsId: 'env-rescue-panel-prod', variable: 'envfile')]) {
                writeFile file: '.env', text: readFile(envfile)
            }
        }
    }

    stage('Build & tag container') {
        steps {
            sh "docker build --build-arg user=pannel --build-arg uid=45 -t simonloudev/rescue-panel:latest ."
            sh "docker tag simonloudev/rescue-panel:latest simonloudev/rescue-panel:$SENTRY_RELEASE"
        }
    }


    stage('Setup project') {
        steps{
            sh "php artisan key:generate"
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

    stage('Sentry version') {
        steps {
            sh 'command -v sentry-cli || curl -sL https://sentry.io/get-cli/ | bash'
            sh "sentry-cli releases new -p laravel -p react $SENTRY_RELEASE"
            sh "sentry-cli releases set-commits $SENTRY_RELEASE --auto"
        }
    }

    stage('Push un Pull on remote Docker container') {
        steps {
            sh "docker push simonloudev/rescue-panel:latest simonloudev/rescue-panel:$SENTRY_RELEASE"
            sh "ssh root@75.119.154.204 docker pull simonloudev/rescue-panel:latest"
        }
    }

    stage('Launch'){
        steps{
            sh "ssh root@75.119.154.204 docker restart rescue-panel"
        }
    }

    stage('Finishing sentry version'){
        steps{
            sh "rm ./public/assets/*"
            sh "ssh root@75.119.154.204 mkdir /tmp/rescue-panel"
            sh "ssh root@75.119.154.204 docker cp rescue-panel:/var/www/public/assets/ /tmp/rescue-panel "
            sh "scp root@75.119.154.204:/tmp/rescue-panel/* ./public/assets/"
            sh "sentry-cli releases -p react files $SENTRY_RELEASE upload-sourcemaps --ext map ./public/assets/"
            sh "sentry-cli releases -p react -p laravel finalize $SENTRY_RELEASE"
            sh "sentry-cli releases -p react -p laravel deploys $SENTRY_RELEASE new -e $SENTRY_ENVIRONMENT"
        }
    }

    stage('Clean'){
        steps{
            sh 'rm ./public/assets/*.js'
            sh 'rm ./public/assets/*.map'
            sh "rm ./public/assets/*.css"
            sh "rm ./public/assets/*.jpg"
            sh "rm .env"
        }
    }
  }
}
