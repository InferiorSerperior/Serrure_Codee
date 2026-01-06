<?php include 'php/url.php' ?>

<!DOCTYPE html>
<html>
<head>
	<?php include 'php/models/head.php' ?>
</head>
<body>
	
	<?php include 'php/models/header.php' ?>

	<?php include 'php/models/sidebar.php'; ?>

    <?php include 'php/models/footer.php' ?>
	
	<div class="container">
		<div class="content">
            <div class="page-title">
                <h2>Porte déverouillée</h2>
	            <?php
				exec("stty -F /dev/serial0 9600 cs8 -cstopb -echo");
				$port = fopen("/dev/ttyUSB0","w");
				fwrite ($port,'OK');
				fclose($port);
				?>
			</div>
		</div>
	</div>
</body>
</html>