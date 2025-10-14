FROM ubuntu:22.04

# Install dependencies
RUN apt-get update && apt-get install -y \
    php8.1 php8.1-cli php8.1-mysql php8.1-mbstring php8.1-xml \
    mysql-server curl git \
    && rm -rf /var/lib/apt/lists/*

# Install Ollama (CPU version)
RUN curl -fsSL https://ollama.com/install.sh | sh

WORKDIR /app
COPY . .

# Install Composer & dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 7860

# Startup script
CMD service mysql start && \
    ollama serve & \
    sleep 5 && \
    ollama pull tinyllama:1.1b && \
    sleep 2 && \
    php -S 0.0.0.0:7860 -t public
