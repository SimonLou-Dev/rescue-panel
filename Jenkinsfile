pipeline {
  agent any
  options {
    skipDefaultCheckout(true)
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
        sh "rm .env"
      }
    }

    stage('Cleaning assets') {
      steps {
        validateDeclarativePipeline 'Jenkinsfile'
        sh 'rm ./public/assets/*.js'
        sh 'rm ./public/assets/*.map'
        sh "rm ./public/assets/*.css"
        sh "rm ./public/assets/*.jpg"
      }
    }

    stage('Write .env [prod]') {
        steps{
            sh "rm .env.testing"
            withCredentials([file(credentialsId: 'lscofd-Prod', variable: 'envfile')]) {
                writeFile file: '.env', text: readFile(envfile)
            }
        }
    }

    stage('Build & tag container') {
        steps {
            sh "docker build --build-arg user=pannel --build-arg uid=45 -t simonloudev/rescue-panel:latest ."
            sh "docker tag simonloudev/rescue-panel:latest simonloudev/rescue-panel:"
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

    stage('Sentry version') {
        environment {
            SENTRY_AUTH_TOKEN = credentials('sentry-auth-token')
            SENTRY_ORG = 'sample-organization-slug'
            SENTRY_PROJECT = 'sample-project-slug'
            SENTRY_ENVIRONMENT = 'production'
        }
        steps {
            sh 'command -v sentry-cli || curl -sL https://sentry.io/get-cli/ | bash'
            sh "export SENTRY_RELEASE=$(sentry-cli releases propose-version)"
            sh "sentry-cli releases new -p laravel -p react $SENTRY_RELEASE"
            sh "sentry-cli releases set-commits $SENTRY_RELEASE --auto"
        }
    }

    stage('Push un Pull on remote Docker container') {
        steps {
            sh "docker push simonloudev/rescue-panel:latest simonloudev/rescue-panel:SENTRY_RELEASE"
            sh "ssh root@75.119.154.204 docker pull simonloudev/rescue-panel:latest"
        }
    }

    stage('Launch'){
        steps{
            sh "ssh root@75.119.154.204 docker stop rescu-panel"
            sh "ssh root@75.119.154.204 docker run -d --rm --env=DISCORD_REDIRECT_URI=https://rescue-panel.simon-lou.com/auth/callback --env=APP_URL=https://rescue-panel.simon-lou.com --volume=rescue-panel:/var/www/storage --network=nginx-proxy  --name rescue-panel simonloudev/rescue-panel:latest"
            sh "sentry-cli releases files $SENTRY_RELEASE upload-sourcemaps ./public/"
            sh "sentry-cli releases finalize $SENTRY_RELEASE"
            sh "sentry-cli releases deploys $SENTRY_RELEASE new -e $SENTRY_ENVIRONMENT"
        }
    }

    stage('Finishing sentry version'){
        steps{
            sh "rm ./public/assets/*"
            sh "ssh root@75.119.154.204 mkdir /tmp/rescue-panel"
            sh "ssh root@75.119.154.204 docker cp rescue-panel:/var/www/public/assets/ /tmp/rescue-panel "
            sh "scp root@75.119.154.204:/tmp/rescue-panel/* ./public/assets/"
            sh "sentry-cli releases files $SENTRY_RELEASE upload-sourcemaps --ext map ./public/assets/"
            sh "sentry-cli releases finalize $SENTRY_RELEASE"
            sh "sentry-cli releases deploys $SENTRY_RELEASE new -e $SENTRY_ENVIRONMENT"
        }
    }
  }
}
