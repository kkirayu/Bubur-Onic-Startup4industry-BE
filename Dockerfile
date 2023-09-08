FROM harbor.merapi.javan.id/testing/php8.1-nginx:1679895971
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer global config allow-plugins.isaac/composer-velocita true
RUN composer global require isaac/composer-velocita
RUN composer velocita:enable http://192.168.88.210:8999/

RUN apt-get update
RUN apt-get install -y \
			php8.1-bcmath \
            php8.1-mbstring \
            php8.1-gd \
            php8.1-ldap

COPY ./nginx/website.conf /etc/nginx/sites-enabled/website.conf

COPY ./ /var/www/project

RUN --mount=type=cache,target=/tmp/cache composer install

RUN /bin/chown -R www-data:www-data $(ls -I vendor)

RUN yarn

RUN yarn build

# Siapkan Entrypoint
COPY start.sh /opt/start.sh
RUN chmod +x /opt/start.sh

# CMD untuk command yang ada returnnya
# ENTRYPOINT sebuah aplikasi / server  biasanya pakai entrypoint

ENTRYPOINT /opt/start.sh

