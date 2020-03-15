# README

## Introduction

This is a simple [Wiki](http://en.wikipedia.org/wiki/Wiki) project to show how to use the [Flight](https://flightphp.com) PHP micro-framework.

It uses files for the individual pages, so no database!  
Every page is a file in the pages directory, in root folder.  
All files have `.md` as the file extension and are using the [Markdown](http://en.wikipedia.org/wiki/Markdown) syntax.

> _WARNING! This project is only an illustration of a development on the [Flight](https://flightphp.com) PHP micro-framework and is not intended to be used in a production environment! I'm not reliable for any damage that could be caused._

## How it works

Before any page creation, please make sure you have write privilege on the `/pages` folder:
```
$ chmod 755 ./pages
```

By default, you have these 4 pages on the Wiki: 

* [[ Home ]]
	* _This is the Home page, it contains links to this readme and two examples pages._
* [[ Another page ]] & [[ My new page ]]
	* _Just some example pages_
* [[ README ]]
	* _Some instructions for this little Wiki_  ←  You are here right now!

### Viewing a page

To view a page you can either:
* Click on a link on another page
* Click on a link in the "page name cloud" (at the top)
* Change the page name in the url:  
For example, change:
```
http://www.example.com/home
```
To:
```
http://www.example.com/A_new_page
```

### Add a page

To add a page, you just have to:

1. Add a link in a page, by adding the code below : 
<pre><code>[<span>[ Name of the new page ]</span>]</code></pre>
2. Click on "Update"
3. Click on the new link
4. Add content in the text field
5. Click on "Create"
6. Done!

### Edit a page ###

To edit a page you just have to:

1. Open the page you want to edit in your browser
2. Click on "Edit" at the bottom
3. Change content in text field
4. Click on "Update"
5. Done!

### Delete a page ###

To delete a page you just have to:

1. Open the page you want to delete in your browser
2. Click on "Delete" at the bottom
3. Confirm the deletion in the pop-up
4. Done!