<h1>Edit page: <?= htmlspecialchars(ucfirst($pageName)) ?></h1>

<form action="/<?= $pageName ?>" method="post">
	<?= Flight::csrf()
	          ->renderFormTokenField(); ?>
	<input type="hidden" name="_method" value="PUT" id="_method">
	<input type="hidden" name="pageName" value="<?= $pageName ?>" id="pageName">

	<textarea name="pageContent" id="pageContentField" rows="20" placeholder="Enter Page Content..."><?= $pageContent; ?></textarea>

	<p><input type="submit" value="Update &rarr;" id="submitButton"></p>
</form>