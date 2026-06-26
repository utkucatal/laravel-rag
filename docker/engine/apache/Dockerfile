FROM httpd:2.4-alpine
WORKDIR "/var/www/html"

RUN apk update && \
    apk add --no-cache \
        nano \
        rsync && \
    rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# httpd.conf runs as www-data; ensure the user/group exist on Alpine.
RUN addgroup -S www-data 2>/dev/null || true; \
    adduser -S -G www-data -H -s /sbin/nologin www-data 2>/dev/null || true

# httpd.conf + vhost.conf are bind-mounted by docker-compose at runtime.
CMD ["httpd-foreground"]
