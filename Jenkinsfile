def workspace = '/var/lib/jenkins/workspace/covid-nien-luan'

pipeline {
    agent any
    stages{
        stage('Update code') {
            steps {
                script {
                    sh 'git checkout dev && git reset --hard && git pull origin dev'
                    GIT_CHANGES = sh(script: 'git log --pretty=format:" - %s (@%an #%h)" HEAD..origin/main',
                                returnStdout: true)
                    sh 'git merge origin/main'
                    // workspace = sh(script: 'pwd', returnStdout: true)
                }
            }
        }
        stage("Install dependency") {
            steps {
                dir(workspace){
                    withEnv(['PATH_NODE=/usr/local/bin/yarn']) {
                        sh "$PATH_NODE install"
                        sh "$PATH_NODE run pre_setup"
                    } 
                }
                
                dir(workspace + '/apps/admin-ui'){
                    script {
                        sh 'npm run build'
                    }
                }
                dir(workspace + '/apps/admin-api'){
                    script {
                        sh 'composer install'
                    }
                }
            }
        }
        stage("Notification"){
            steps {
                telegramSend (message: 'Covid NienLuan Web CI \nCode has been shipped successfully \nCHANGES:\n' + GIT_CHANGES, chatId: -579973777)
            }
        }
    }
    
}