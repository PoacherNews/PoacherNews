<html>
	<head>
		<?php include 'includes/globalHead.html' ?>
	</head>
	<body>
		<?php
			include('includes/header.php');
			include('includes/nav.php');
			include('util/db.php');
		?>
		<?php
			$sql = "SELECT * FROM Articles WHERE Category = \"".$_GET['section']."\";";
		?>
		<pre>
			The requested section in the _GET is: <?php echo $_GET['section']; ?>

			The formatted SQL call is: <?php echo $sql; ?>

			Here's a list of all article IDs matching that section:
			<?php
				$result = mysqli_query($db, $sql);
				if(mysqli_num_rows($result) == 0) {
   					print "Couldn't find any rows for this section";
    			}
    			$data = array();
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Put each returned row into a PHP array
    				$data[] = $row;
				}
				print_r($data);
			?>
		</pre>
		<?php include('includes/footer.html'); ?>
	</body>
</html>