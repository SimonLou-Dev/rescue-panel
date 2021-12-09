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

  }
}
