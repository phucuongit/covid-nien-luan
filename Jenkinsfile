def workspace = ''

pipeline {
    agent any
    stages{
        stage('Update code') {
            steps {
                script {
                    sh 'git checkout dev && git reset --hard && git pull origin/dev'
                    GIT_CHANGES = sh(script: 'git log --pretty=format:" - %s (@%an #%h)" HEAD..origin/main',
                                returnStdout: true)
                    sh 'git merge origin/main'
                    workspace = sh(script: 'pwd', returnStdout: true)
                }
            }
        }
        stage("Install dependency") {
            steps {
                dir(workspace){
                    echo pwd
                    sh 'yarn && yarn run pre_setup'   
                }
            
               
                dir('apps/admin-api'){
                    script {
                        sh 'composer install'
                    }
                }
            }
        }

        stage('Deploy') {
            steps {
              
                nodejs('Node_14'){
                    sh 'yarn run admin:dev'
                }
            }
        }
    }
    
}