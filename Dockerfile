FROM frankenphp/base:latest
WORKDIR /app
COPY . .
RUN composer install --optimize-autoloader --no-dev
EXPOSE 8080
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
