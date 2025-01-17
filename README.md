[![Latest Stable Version](https://poser.pugx.org/canerdogan/imgproxy/v/stable)](https://packagist.org/packages/canerdogan/imgproxy)[![Total Downloads](https://poser.pugx.org/canerdogan/imgproxy/downloads)](https://packagist.org/packages/canerdogan/imgproxy)
# img-proxy

Laravel Service Provider for Golang ImgProxy micro-service https://evilmartians.com/chronicles/introducing-imgproxy

## Install
Works with Laravel 5.1 - 5.7 / PHP 7.0 - 7.2, probably 7.3 too

- `composer require canerdogan/imgproxy`
- copy the ServiceProvider to the providers array in config/app.php. Laravel 5.5 users with  auto-discovery may skip this step
```php
CanErdogan\ImgProxy\ImgProxyServiceProvider::class,
```
- copy the package config to your local config with the publish command:
```php
php artisan vendor:publish --provider="CanErdogan\\ImgProxy\\ImgProxyServiceProvider"
```

- to generate the secrets, you can use this command: `echo $(xxd -g 2 -l 64 -p /dev/random | tr -d '\n')`

- env file:
```bash
# img-proxy.base_url
IMGPROXY_URL=https://img-proxy-example.com
# your img-proxy key
IMGPROXY_KEY=943b421c9eb07c830af81030552c86009268de4e532ba2ee2eab8247c6da0881
# your img-proxy salt
IMGPROXY_SALT=520f986b998545b4785e0defbc4f3c1203f22de2374a3d53cb7a7fe9fea309c5
```

**This package does not cover** the `Authorization` header.

## Usage

helper:
```php
imgProxy('https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.jpg', 640, 360)
```

```php
use CanErdogan\ImgProxy\Contracts\ImageSignatureInterface;
use CanErdogan\ImgProxy\Image;

Route::get('/img-test', function () {
    $path      = 'https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.jpg';
    $width     = 640;
    $height    = 360;
    $pic       = new Image;
    $pic->setOriginalPictureUrl($path)
        ->setWidth($width)
        ->setHeight($height)
        ->setResize('fit')
        ->setGravity('no')
        ->setEnlarge(0)
        ->setExtension('png');
    app()->instance(Image::class, $pic);
    $signature = app(ImageSignatureInterface::class);

    echo '
    Resized: <img src="' . config('img-proxy.base_url') . $signature->take() . '" alt="Resized">
    <br>
    Original: <img src="' . $path . '" alt="Original">
    ';

});
```

