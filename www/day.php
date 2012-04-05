<html>
	<head>
		<title>SAR Graphs</title>
	</head>

	<body>
                <p align='center'>
			<b>Past 24 hours</b><br>
			<font size='1'>
			<?php
				echo 'Timezone: ' . shell_exec('date +%Z'). "\n";
			?>
			<br><br>
			</font>
                        <img src='graph.php?src=ram&range=day' border='0'><br><br>
                        <img src='graph.php?src=load&range=day' border='0'><br><br>
                        <img src='graph.php?src=cpu&range=day' border='0'>
                </p>
	</body>
</html>
