<div id="pageContent">
	<?= FlightWiki::renderMarkdownHtml($pageContent); ?>
</div>

<?php if (!isset($hideButtons)): ?>
	<div id="buttons">
		<p>
			<a href="/<?= $pageName ?>/edit">Edit Page</a>

			<?php if (strtolower($pageName) != 'home'): ?>
				|
				<a href="/<?= $pageName ?>"
				   onclick="if (confirm('Are you sure?')) {
							var f = document.createElement('form');

							f.style.display = 'none';
							f.method = 'POST';
							f.action = this.href;
							this.parentNode.appendChild(f);

							var m = document.createElement('input');

							m.setAttribute('type', 'hidden');
							m.setAttribute('name', '_method');
							m.setAttribute('value', 'DELETE');
							f.appendChild(m);
							f.submit();
						}
						return false;">Delete Page</a>
			<?php endif; ?>
		</p>
	</div>
<?php endif; ?>