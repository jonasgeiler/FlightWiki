<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>FlightWiki | <?= empty($pageName) ? 'Flight Example Website' : ucfirst($pageName); ?></title>

		<link href='http://fonts.googleapis.com/css?family=Mr+Dafoe' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="icon" type="image/png" href="<?= $baseUrl ?>public/img/favicon.png" />
		<link rel="stylesheet" href="<?= $baseUrl ?>public/css/normalize.css">
		<link rel="stylesheet" href="<?= $baseUrl ?>public/css/main.css">
	</head>

	<body>
		<div id="container">
			<div id="header">
				<div id="title"><a href="/" style="color: #000;">Flight Wiki</a></div>
				<div id="subtitle">
					<p>An example website for the Flight micro-framework</p>
				</div>
				<div id="nav">
					<p>
						<?= FlightWiki::renderPagesCloud('') ?>
					</p>
				</div>
			</div>

			<div id="content">
				<?= $content ?>
			</div>

			<div id="footer">
				<div>
					<p>
						Copyright &copy;
						<a href="https://skayo.dev" target="_blank">Skayo</a> (FlightWiki),
						<a href="http://www.mikecao.com/" target="_blank">Mike Cao</a> (Original Website Layout)
					</p>
					<p>
						Inspired by
						<a href="https://github.com/sofadesign/limonade-wiki-example" target="_blank">Wikir</a>
						for
						<a href="https://github.com/sofadesign/limonade/" target="_blank">Limonade.php</a>
					</p>
				</div>

				<a class="github-button" href="https://github.com/Skayo/FlightWiki" data-icon="octicon-star" data-show-count="true" aria-label="Star Skayo/FlightWiki on GitHub">Star</a>
				<a class="github-button" href="https://github.com/Skayo/FlightWiki/fork" data-icon="octicon-repo-forked" data-show-count="true" aria-label="Fork Skayo/FlightWiki on GitHub">Fork</a>
			</div>

			<script async defer src="https://buttons.github.io/buttons.js"></script>
		</div>
	</body>
</html>