pipeline {
  agent {
    kubernetes {
      inheritFrom "build-go build-docker"
    }
  }

  stages {

    stage('Prepare') {
      environment {
        GOPROXY = "https://goproxy.cn,direct"
      }

      steps {
        container('golang') {
          sh "sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories"
          sh "apk --no-cache add make"
        }

        container('docker') {
          sh "sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories"
          sh "apk --no-cache add make"
        }

        container('golang') {
          sh "make dep-backend"
        }
      }
    }

    stage('UnitTest') {
      parallel {
        stage("Backend UnitTest") {
          steps {
            container('golang') {
              sh "make test-backend"
            }
          }
        }

      }
    }

    stage('Build Backend') {
      steps {
        container('golang') {
          sh "make build-backend"
        }
      }
    }

    stage('Build Image') {
      parallel {
        stage('build qucheng') {
          steps {
            container('docker') {
              sh "make build-qucheng"
            }
          }
        }

        stage('build api') {
          when {
            anyOf {
              tag ''
              branch 'test'
            }
          }

          steps {
            container('docker') {
              sh "make build-api"
            }
          }
        }

      }

    } // end Build Image
    
    stage("Publish Image") {
      parallel {
        stage('qucheng image') {
          steps {
            container('docker') {
              withDockerRegistry(credentialsId: 'hub-qucheng-push', url: 'https://hub.qucheng.com') {
                sh "make push-qucheng"
              }
            }
          }
        }
        stage('cne-api image') {
          when {
            anyOf {
              tag ''
              branch 'test'
            }
          }
          steps {
            container('docker') {
              withDockerRegistry(credentialsId: 'hub-qucheng-push', url: 'https://hub.qucheng.com') {
                sh "make push-api"
              }
            }
          }
        } 
      } // end parallel
    } // end Publish Image

    stage("Deploy") {
      when {
        branch 'test'
      }

      environment { 
          CNE_API_TOKEN = credentials('dev-haogs-cn-token')
          CNE_API_HOST = "https://console.dev.haogs.cn"
      }

      stages {
        stage("Deploy Qucheng") {
          steps {
            script{
              def body = ["namespace": "cne-system", "name": "qucheng", "channel": "test", "chart": "qucheng"]

              def response = httpRequest \
                             httpMode: 'POST' ,
                             ignoreSslErrors: true,
                             contentType: 'APPLICATION_JSON',
                             requestBody: groovy.json.JsonOutput.toJson(body),
                             customHeaders: [[name: 'X-Auth-Token', value: env.CNE_API_TOKEN]],
                             url: env.CNE_API_HOST + "/api/cne/app/restart"

              println("Content: "+response.content)
            }
          }
        }

        stage("Deploy Cne-api") {
          steps {
            script{
              def body = ["namespace": "cne-system", "name": "cne-api", "channel": "test", "chart": "cne-api"]

              def response = httpRequest \
                             httpMode: 'POST' ,
                             ignoreSslErrors: true,
                             contentType: 'APPLICATION_JSON',
                             requestBody: groovy.json.JsonOutput.toJson(body),
                             customHeaders: [[name: 'X-Auth-Token', value: env.CNE_API_TOKEN]],
                             url: env.CNE_API_HOST + "/api/cne/app/restart"

              println("Content: "+response.content)
            }
          }
        }
      }

    } // end Deploy

  }
}
