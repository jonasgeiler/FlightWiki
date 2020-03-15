<?php
require 'flight/Flight.php';

Flight::path('lib'); // Add the "lib" directory path to automatically load classes from there

// Setup and configuration (happens right before starting the framework)
Flight::before('start', function () {
	session_start(); // Start Session for CSRF Token

	// Check if running on localhost...
	$localhost = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
	if ($localhost) {
		// ... and enable error logging, if yes
		Flight::set('flight.log_errors', true);
		ini_set('error_log', './errors.log');
	}

	Flight::set('pages_dir', './pages'); // Set the path of the pages directory
});

// Before sending a response ...
Flight::before('stop', function () {
	// ... render the layout and pass the base url to the view.

	$base = Flight::get('flight.base_url'); // Get configuration

	if (is_null($base)) // Check if base url was set in config ...
		$base = Flight::request()->base; // ... if not, get the base url from the current request.

	Flight::view()->set('baseUrl', $base); // Set the view variable

	if (Flight::view()
	          ->has('content')) // If the "content" view variable has been set (with Flight::render('some_view', null, 'content')) ...
		Flight::render('layouts/default'); // ... render the layout and insert the content there.
});

// Handle not found error
Flight::map('notFound', function () {
	// Some hacky stuff to render with the default layout but some special content:

	// Set view variables
	Flight::view()
	      ->set('pageName', 'Not Found');
	Flight::view()
	      ->set('pageContent', "# Not Found\nThe page you have requested could not be found.");
	Flight::view()
	      ->set('hideButtons', true); // Set this view variable to hide the "Edit/Delete button box"

	Flight::render('show', null, 'content'); // Render the "show" view
});

// Special function to create a file path by concatenating the given arguments.
// Use like this: Flight::filePath('some', 'path', 'to', 'somewhere');
Flight::map('filePath', function ($path) {
	$args = func_get_args();
	$ds = '/';
	$win_ds = '\\';
	$n_path = count($args) > 1 ? implode($ds, $args) : $path;

	if (strpos($n_path, $win_ds) !== false)
		$n_path = str_replace($win_ds, $ds, $n_path);

	$n_path = preg_replace('/' . preg_quote($ds, $ds) . '{2,}' . '/', $ds, $n_path);

	return $n_path;
});

Flight::register('csrf', 'Csrf'); // Register the CSRF-Class so it can be used everywhere with Flight::csrf()

//------

// Home
Flight::route('/', function () {
	Flight::redirect('/home'); // Redirect to "GET /@page" where the actual home page is
});

// Show a Page
Flight::route('GET /@page', function ($pageName) {
	if ($page = FlightWikiPage::find($pageName)) { // Check if the page exists and grab an instance of FlightWikiPage, if so.
		Flight::csrf()
		      ->unsetToken(); // Unset the CSRF Token, to get a new one for the form

		// Set view variables
		Flight::view()
		      ->set('pageName', $page->name());
		Flight::view()
		      ->set('pageContent', $page->content());

		Flight::render('show', null, 'content'); // Render the "show" view

		return;
	}

	Flight::redirect("/$pageName/new"); // If page was not found, redirect to the new page form
});

// New Page Form
Flight::route('/@page/new', function ($pageName) {
	// Set view variables
	Flight::view()
	      ->set('pageName', $pageName);

	Flight::render('new', null, 'content'); // Render the "new" view
});

// Create a Page (when submitting the new page form)
Flight::route('POST /@page', function () {
	if (Flight::csrf()
	          ->requireValidToken()) { // Check if CSRF token is valid
		// Get the POST data
		$pageName = Flight::request()->data->pageName;
		$pageContent = Flight::request()->data->pageContent;

		if (FlightWiki::isSpam($pageContent)) // Detect if the submitted content contains spam ...
			Flight::halt(403, 'Spam detected!'); // ... and send a 403 (FORBIDDEN), if yes.

		// Create a new instance of FlightWikiPage and set the name and the content
		$page = new FlightWikiPage();
		$page->name($pageName);
		$page->content($pageContent);

		if ($page->save() !== false) { // Save the new page and check if succeeded
			Flight::redirect('/' . $page->name()); // Redirect to the new page

			return;
		}

		throw new Exception('An error occurred. Unable to delete this page. Please check "./pages" dir is writable!'); // Send a 500 (INTERNAL SERVER ERROR), if deleting the page has failed
	}
});


// Edit Page Form
Flight::route('/@page/edit', function ($pageName) {
	if ($page = FlightWikiPage::find($pageName)) { // Check if the page exists and grab an instance of FlightWikiPage, if so.
		Flight::csrf()
		      ->unsetToken(); // Unset the CSRF Token, to get a new one for the form

		// Set view variables
		Flight::view()
		      ->set('pageName', $page->name());
		Flight::view()
		      ->set('pageContent', $page->content());

		Flight::render('edit', null, 'content'); // Render the "edit" view

		return;
	}

	Flight::notFound(); // If page wasn't found, send a 404 (NOT FOUND)
});

// Update a Page (when submitting the edit page form)
/* Note: This request is actually a POST request, done by the edit page form, but because we pass
   a hidden "_method" parameter with "PUT" in it, Flight recognizes it as a PUT request */
Flight::route('PUT /@page', function ($pageName) {
	// Get the PUT data
	$pageContent = Flight::request()->data->pageContent;

	if (FlightWiki::isSpam($pageContent)) // Detect if the submitted content contains spam ...
		Flight::halt(403, 'Spam detected!'); // ... and send a 403 (FORBIDDEN), if yes.

	if ($page = FlightWikiPage::find($pageName)) { // Check if the page exists and grab an instance of FlightWikiPage, if so.
		if (Flight::csrf()
		          ->requireValidToken()) { // Check if CSRF token is valid

			// Set the updated name and the content of the page
			$page->name($pageName);
			$page->content($pageContent);

			if ($page->save() !== false) { // Save the updated page and check if succeeded
				Flight::redirect('/' . $page->name()); // Redirect to the updated page

				return;
			}

			throw new Exception('An error occurred. Unable to delete this page. Please check "./pages" dir is writable!'); // Send a 500 (INTERNAL SERVER ERROR), if deleting the page has failed
		}
	}

	Flight::notFound(); // If page wasn't found, send a 404 (NOT FOUND)
});

// Delete a Page
Flight::route('DELETE /@page', function ($pageName) {
	if (strtolower($pageName) == 'home') // Check if the user tries to delete the home page ...
		Flight::halt(403, "Home page can't be deleted."); // ... and send an 403 (FORBIDDEN), if yes, because the home page should always stay.

	if ($page = FlightWikiPage::find($pageName)) { // Check if the page exists and grab an instance of FlightWikiPage, if so.
		$page->name($pageName);

		if ($page->destroy()) { // Save the updated page and check if succeeded
			Flight::redirect('/'); // Redirect to the home page

			return;
		}

		throw new Exception('An error occurred. Unable to delete this page. Please check "./pages" dir is writable!'); // Send a 500 (INTERNAL SERVER ERROR), if deleting the page has failed
	}

	Flight::notFound(); // If page wasn't found, send a 404 (NOT FOUND)
});

//------

Flight::start(); // Start the framework
