# FlightWiki

This is a simple [Wiki](http://en.wikipedia.org/wiki/Wiki) project to show how to use the [Flight](https://flightphp.com) PHP micro-framework.

It uses files for the individual pages, so no database!  
Every page is a file in the pages directory, in root folder.  
All files are using the [Markdown](http://en.wikipedia.org/wiki/Markdown) syntax.

> _WARNING! This project is only an illustration of a development on the [Flight](https://flightphp.com) PHP micro-framework and is not intended to be used in a production environment! I'm not reliable for any damage that could be caused._

## Screenshot

![Screenshot](https://raw.githubusercontent.com/Skayo/FlightWiki/master/public/img/Screenshot.png)

## Requirements

Flight requires PHP 5.3 or greater, and so does FlightWiki.

## Installation

[Download](https://github.com/Skayo/FlightWiki/archive/master.zip) and unzip the archive, then upload the content to the root folder of your web server (for example `C:\WAMP\www` or `/var/www`).  
There is already a [.htaccess](https://github.com/Skayo/FlightWiki/blob/master/.htaccess) so it should work in Apache, but if you use Nginx instead, add the following to your server declaration:
```
server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}
```


Running it locally is also possible with the PHP built-in webserver.  
You can start it with this command:
```
$ php -S localhost:8080
```
And then visit `http://localhost:8080` in your browser.


The rest of the instructions can be found on the README page (`http://localhost:8080/readme`) in the wiki!

## License

FlightWiki is released under the [MIT License](https://github.com/Skayo/FlightWiki/blob/master/LICENSE.md), just like Flight itself.