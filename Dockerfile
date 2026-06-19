FROM php:8.2-apache

# Install bc for math in bash script
RUN apt-get update && apt-get install -y \
    bc \
    iputils-ping \
    iproute2 \
    procps \
    && rm -rf /var/lib/apt/lists/*

# Create data directory
RUN mkdir -p /data && chmod 777 /data

# Copy dashboard
COPY dashboard.php /var/www/html/index.php

# Copy and make collector executable
COPY collector.sh /collector.sh
RUN chmod +x /collector.sh

# Start both the bash collector (background) and Apache
CMD ["/bin/bash", "-c", "/collector.sh & apache2-foreground"]