def workspace = ''

pipeline {
    agent any
    stages{
        stage('Update code') {
            steps {
                script {
                    sh 'git checkout dev && git reset --hard && git fetch'
                    GIT_CHANGES = sh(script: 'git log --pretty=format:" - %s (@%an #%h)" HEAD..origin/main',
                                returnStdout: true)
                    sh 'git merge origin/main'
                    workspace = sh(script: 'pwd', returnStdout: true)
                }
            }
        }
        stage("Install dependency") {
            steps {
              
                nodejs('Node_14'){
                    sh 'yarn'
                }
                
    //             dir(workspace){
    // s               script {
    //                     sh 'cd $(pwd) && ./deploy.sh'
    //                 }
    //             }
               
                dir('apps/admin-api'){
                    script {
                        sh 'composer install'
                    }
                }
            }
        }
        stage('Build'){
            steps {
                echo 'Building'
            }
        }
        stage('Test'){
            steps {
                echo 'Testing'
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