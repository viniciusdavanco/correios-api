# Use uma imagem base oficial do PHP com Apache
FROM php:8.2-apache

# Instale as dependências do sistema necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos do projeto para o diretório de trabalho
COPY . .

# Instale as dependências do PHP
RUN composer install --no-dev --optimize-autoloader

# Configure o Apache para usar o diretório correto (se necessário, dependendo da sua estrutura)
# Para a sua aplicação, que tem index.php na raiz, o padrão do Apache deve funcionar.
# Se você precisar de um diretório público específico (ex: public/), adicione um arquivo .htaccess ou configure o vhost.

# Exponha a porta 80 (padrão do Apache)
EXPOSE 80

# O comando padrão do PHP-Apache já inicia o servidor
CMD ["apache2-foreground"]
