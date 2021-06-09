## Image Meta Extractor API [IMGEXIF]
Extract [EXIF](https://exiftool.org/TagNames/EXIF.html) information from an image or url. 
EXIF is sometimes called metadata, is a collection of information that is stored by the camera at the moment you take a photo.

### API Documentation
Get Postman API documentation [here](https://documenter.getpostman.com/view/11223504/TzY7dYrZ)

### Initial Setup
Run this commands -
```
git clone https://github.com/rakibdevs/image-meta-extractor-api.git
cd image-meta-extractor-api
cp .env.example .env
```
Create a database and update `.env` with database credentials
```
composer update
php artisan key:generate
php artisan migrate
```

Now serve 
```
php artisan serve
```
See [this vue.js repository](https://github.com/RakibDevs/vue-image-meta) to connect this API. 
