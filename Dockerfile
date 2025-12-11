# Use uma imagem base oficial do PHP com Apache
FROM php:8.2-apache

# Instale as dependências do sistema necessárias (git, unzip, libzip-dev)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Instale o Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos de configuração do Composer
COPY composer.json composer.lock ./

# Instale as dependências do PHP (cria a pasta vendor/)
RUN composer install --no-dev --optimize-autoloader

# Copie o restante dos arquivos do projeto
COPY . .

# Exponha a porta 80 (padrão do Apache)
EXPOSE 80

# O comando padrão do PHP-Apache já inicia o servidor
CMD ["apache2-foreground"]
