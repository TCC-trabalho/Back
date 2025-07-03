FROM php:8.1-cli

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl

# Instala extensões PHP necessárias (por exemplo, pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# Instala o Composer (copiando de uma imagem oficial do Composer)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia arquivos de dependências e instala
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress

# Copia todo o projeto
COPY . .

# Expõe a porta do "php artisan serve"
EXPOSE 8000

# Comando para rodar a aplicação Laravel no modo "dev server"
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]