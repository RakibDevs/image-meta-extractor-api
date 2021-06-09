## Image Meta Extractor API [IMGEXIF]
Extract [EXIF](https://exiftool.org/TagNames/EXIF.html) information from an image or url. 
EXIF is sometimes called metadata, is a collection of information that is stored by the camera at the moment you take a photo.

### API Documentation
Get Postman API documentation [here](https://documenter.getpostman.com/view/11223504/TzY7dYrZ)

### Initial Setup
Run this commands -
```bash
git clone https://github.com/rakibdevs/image-meta-extractor-api.git
cd image-meta-extractor-api
cp .env.example .env
```
Create a database and update `.env` with database credentials
```bash
composer update
php artisan key:generate
php artisan migrate
```

Now serve 
```bash
php artisan serve
```

Run tests
```bash
php artisan test
```
See [this vue.js repository](https://github.com/RakibDevs/vue-image-meta) to connect this API. 

Example API response - 
```php
{
  "id": 116,
  "title": null,
  "src": "images/2021-06/60bfc74821b79.jpg",
  "actual_src": null,
  "height": 3024,
  "width": 4032,
  "mime_type": "image/jpeg",
  "created_at": "2021-06-08T19:38:49.000000Z",
  "updated_at": "2021-06-08T19:38:49.000000Z",
  "image_src": "http://127.0.0.1:8000/images/2021-06/60bfc74821b79.jpg",
  "created_ago": "1 second ago",
  "meta": {
    "id": 73,
    "image_id": 116,
    "author": {
      "Author": "",
      "Copyright": ""
    },
    "camera": {
      "Make": "samsung",
      "Model": "SM-G973F",
      "Exposure": "1/100",
      "Aperture": "252/100",
      "Focal Length": "432/100",
      "Focal Length (35mm)": 26,
      "ISO Speed": 320,
      "Shutter Speed": "1/100",
      "Flash": "No Flash"
    },
    "exif": {
      "Image Unique ID": "L12XLLD01VM",
      "Orientation": "Rotate 90 CW",
      "Horizontal Resolution": "72/1",
      "Vertical Resolution": "72/1",
      "Resolution Unit": "inches",
      "Software": "Elements Organizer 16.0",
      "Modify Date": "2020:03:30 20:48:09",
      "YCbCr Positioning": "Centered",
      "FNumber": "240/100",
      "Exposure Program": "Program AE",
      "Original Date": "2019:10:18 08:40:35",
      "Created Date": "2019:10:18 08:40:35",
      "Brightness": "599/100",
      "Metering Mode": "Program AE",
      "Latitude": 42.34,
      "Longitude": 3.84
    },
    "created_at": "2021-06-08T19:38:49.000000Z",
    "updated_at": "2021-06-08T19:38:49.000000Z"
  }
}
```
