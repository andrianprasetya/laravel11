pipeline {
    agent any

    environment {
        DB_PASSWORD = credentials('db-password')
        PHP = "/usr/bin/php"
        COMPOSER = "/usr/local/bin/composer"
        NODE = "/usr/bin/node"
        NPM = "/usr/bin/npm"
    }

    stages {
        stage('Checkout') {
            steps {
                echo "Checking out source code..."
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                echo "Installing Composer dependencies..."
                sh "sudo ${COMPOSER} install --no-interaction --prefer-dist --optimize-autoloader"
            }
        }

        stage('Build Frontend') {
        //wajib karena ada test yg nge get tampilan frontend
            steps {
                echo "Building frontend..."
                sh "${NPM} install"
                sh "${NPM} run build" // atau `npm run prod`
            }
        }

        stage('Set Up Testing Env') {
            steps {
                echo "Setting up Laravel environment for testing..."
                sh "cp .env.testing .env || true" // Gunakan .env.testing untuk unit test
                sh "sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/' .env"
                sh "${PHP} artisan migrate --env=testing --force"
                sh "${PHP} artisan config:clear"
                sh "${PHP} artisan cache:clear"
                sh "${PHP} artisan view:clear"
                sh "${PHP} artisan key:generate"

            }
        }

        stage('Run Unit Tests') {
            steps {
               echo "Running PHPUnit tests..."
               sh "${PHP} artisan test"
            }
        }

        stage('Deploy to Production') {
              when {
                  expression { currentBuild.currentResult == 'SUCCESS' }
              }
              steps {
                  echo "Deploying code..."
                   sh """
                      rsync -av \
                      --exclude='.env' \
                      --exclude='.env.testing' \
                      --exclude='.git' \
                      --exclude='.gitignore' \
                      --exclude='tests' \
                      --exclude='phpunit.xml' \
                      --exclude='node_modules' \
                      --exclude='storage/logs' \
                      --exclude='storage/framework/cache' \
                      --exclude='storage/framework/sessions' \
                      --exclude='storage/framework/testing' \
                      --exclude='storage/framework/views' \
                      --exclude='vendor' \
                      ./ /var/www/laravel11
                      """   // replace directory project prod
                  dir("/var/www/laravel11") {
                      sh "${COMPOSER} install --no-dev --optimize-autoloader"
                      sh "${PHP} artisan config:cache"
                      sh "${PHP} artisan migrate --force"
                  }
            }
        }

        stage('Reload Webserver') {
            steps {
                echo "Restarting web server..."
                sh "sudo systemctl reload apache2"
            }
        }
    }

    post {
        always {
             echo 'Cleaning Jenkins workspace...'
             cleanWs()
        }
        success {
            echo "✅ Deploy succeeded!"
        }
        failure {
             echo "❌ Deploy failed."
        }
    }
}
