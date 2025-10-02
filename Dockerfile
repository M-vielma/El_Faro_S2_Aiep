# Dockerfile (RAÍZ)
FROM php:8.2-cli
WORKDIR /app
COPY . /app

# Config de PHP orientada a prod (silencia notices)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Render expone la variable $PORT. Si no estuviera, usa 10000.
EXPOSE 10000
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public server.php"]
