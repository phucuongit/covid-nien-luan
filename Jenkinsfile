pipeline {
    agent any
    stages{
        stage('Update code') {
            steps {
                script {
                    sh 'git checkout main && git reset --hard && git fetch'
                    GIT_CHANGES = sh(script: 'git log --pretty=format:" - %s (@%an #%h)" HEAD..origin/main',
                                returnStdout: true)
                    sh 'git merge origin/main'
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
                echo 'Deploying....'
            }
        }
    }
    
}