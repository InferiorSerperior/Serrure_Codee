<header class="top-bar">
	<div class="logo-container">
		<div class="logo-content">
			<img class="logo-img" src="img/logo.png" width="18px" height="28px">
			<h1 class="logo-text">Serrure Codée</h1>
		</div>
	</div>
	<div class="link-container">		
			<span class="link-text origin"><?= $origin ?></span>
			<?php echo $result = (!empty($pageName)) ? '<span class="link-text pageName">' . $pageName . '</span>' : ""; ?>
	</div>
</header>