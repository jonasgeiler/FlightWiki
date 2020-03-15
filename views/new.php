<h1>Create page: <?= htmlspecialchars(ucfirst($pageName)) ?></h1>

<form action="/<?=$pageName?>" method="post">
	<?= Flight::csrf()
	          ->renderFormTokenField(); ?>
	<input type="hidden" name="pageName" value="<?= $pageName; ?>" id="pageName">

	<textarea name="pageContent" id="pageContentField" rows="20" placeholder="Enter Page Content..."></textarea>

	<p>
		<button class="button cancel" onclick="event.preventDefault();window.location.href = '/'">Cancel</button>
		<input type="submit" value="Create &rarr;" class="button">
	</p>
</form>