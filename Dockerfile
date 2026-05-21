# Usamos una imagen EOL (End of Life) real de Debian Lenny alojada en Docker Hub
FROM docker.io/debian/eol:lenny

# Forzar los repositorios hacia el archivo histórico oficial de Debian
RUN echo "deb http://archive.debian.org/debian/ lenny main contrib non-free" > /etc/apt/sources.list

# Instalar los paquetes requeridos quitando 'php5-mysqli' ya que viene integrado en 'php5-mysql'
RUN apt-get update && apt-get install -y --force-yes \
    apache2-mpm-prefork \
    libapache2-mod-php5 \
    php5-mysql \
    php5-gd \
    php5-curl \
    && apt-get clean

# Configurar el php.ini con los parámetros del phpinfo.pdf
RUN sed -i 's/register_globals = Off/register_globals = On/g' /etc/php5/apache2/php.ini \
 && sed -i 's/magic_quotes_gpc = Off/magic_quotes_gpc = On/g' /etc/php5/apache2/php.ini \
 && sed -i 's/memory_limit = 128M/memory_limit = 24M/g' /etc/php5/apache2/php.ini

EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]