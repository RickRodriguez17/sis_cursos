# Aula Viva — plataforma de cursos

Plataforma Laravel 13 + Livewire 3 para vender cursos en video con precios en bolivianos (Bs). El checkout agrupa varios cursos en una sola orden y genera un único QR.

## Instalación

Requiere PHP 8.4+, Composer, Node.js 22.12+ y SQLite o MySQL/MariaDB.

### Instalación del entorno en Ubuntu 22.04

Los siguientes comandos se verificaron directamente en el host. Son idempotentes:

```bash
sudo DEBIAN_FRONTEND=noninteractive apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y software-properties-common ca-certificates curl unzip git
sudo add-apt-repository -y ppa:ondrej/php
sudo DEBIAN_FRONTEND=noninteractive apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y \
    php8.4-cli php8.4-common php8.4-mbstring php8.4-xml php8.4-curl \
    php8.4-sqlite3 php8.4-zip php8.4-intl php8.4-gd php8.4-bcmath \
    php8.4-readline

if ! command -v composer >/dev/null 2>&1; then
    curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/tmp --filename=composer
    sudo install -m 0755 /tmp/composer /usr/local/bin/composer
    rm -f /tmp/composer
fi

curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y nodejs
```

```bash
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

SQLite es la opción de desarrollo por defecto. Para MySQL configura `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`.

## Uso

El catálogo público está en `/cursos`. Los clientes pueden registrarse, agregar varios cursos al carrito y generar una orden con un único pago. Después de confirmar el pago, las inscripciones se crean automáticamente y aparecen en `/mis-cursos`.

Los administradores gestionan el catálogo desde `/admin/cursos`: pueden crear, editar, publicar, despublicar y eliminar cursos, además de administrar sus videos, previews y orden. Cada lección requiere un archivo de video subido manualmente; se aceptan MP4, WebM y MOV.

## Pagos

`PAYMENT_GATEWAY=fake` muestra un QR SVG generado por el sistema y ofrece “Simular pago confirmado”.

Para Libélula configura:

```dotenv
PAYMENT_GATEWAY=libelula
LIBELULA_ENDPOINT=
LIBELULA_TOKEN=
LIBELULA_WEBHOOK_SECRET=
```

El contrato exacto de endpoints y campos de Libélula queda marcado como TODO en `app/Services/LibelulaGateway.php`, porque no se proporcionaron credenciales ni documentación verificable. Antes de producción se debe confirmar el endpoint de creación, consulta, firma y formato del QR.

El webhook público es `POST /pagos/webhook/{gateway}`. Si `LIBELULA_WEBHOOK_SECRET` está configurado, requiere el header `X-Webhook-Secret`; una firma ausente o incorrecta se rechaza con HTTP 401. Si no hay secreto configurado, el evento se acepta y se registra una advertencia.

## Usuarios demo

- Administrador: `admin@aulaviva.test` / `password`
- Cliente: `cliente@aulaviva.test` / `password`

## Seguridad de videos

Los archivos de video se guardan en el disco privado `local`, fuera de `/public/storage`. La página `/cursos/{curso}/lecciones/{leccion}` muestra un reproductor propio y entrega los bytes desde una ruta de streaming autorizada. El servidor valida que el usuario tenga una inscripción o que la lección sea preview antes de servir el archivo. El streaming soporta rangos HTTP para permitir adelantar videos grandes.

## Subidas de video

La aplicación limita los videos a 512 MB y Livewire usa una carga temporal privada con validación de tipo MIME. Para desarrollo con el servidor integrado de PHP, inicia el proceso con límites suficientes:

```bash
php -c php-dev.ini \
    artisan serve --host=127.0.0.1 --port=8000
```

En producción, configura como mínimo los siguientes valores en `php.ini`:

```ini
upload_max_filesize = 512M
post_max_size = 520M
max_execution_time = 300
memory_limit = 512M
```

Si usas nginx, agrega al bloque correspondiente:

```nginx
client_max_body_size 520M;
```

Después de modificar PHP-FPM o nginx, reinicia el servicio correspondiente. Si el archivo supera estos límites, el formulario muestra un error de validación en lugar de intentar guardar una carga incompleta.
