<h1>Create page: <?= htmlspecialchars(ucfirst($pageName)) ?></h1>

<form action="/<?=$pageName?>" method="post">
	<?= Flight::csrf()
	          ->renderFormTokenField(); ?>
	<input type="hidden" name="pageName" value="<?= $pageName; ?>" id="pageName">

	<textarea name="pageContent" id="pageContentField" rows="20" placeholder="Enter Page Content..."></textarea>

	<p><input type="submit" value="Create &rarr;" id="submitButton"></p>
</form>