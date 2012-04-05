<html>
	<head>
		<title>SAR Graphs</title>
	</head>

	<body>
		<p align='center'>
			<br><b>All Time</b><br><br>
                        <font size='1'>
                        <?php
                                echo 'Timezone: ' . shell_exec('date +%Z'). "\n";
                        ?>      
                        <br><br>
                        </font>
			<img src='graph.php?src=ram&range=all' border='0'><br><br>
			<img src='graph.php?src=load&range=all' border='0'><br><br>
			<img src='graph.php?src=cpu&range=all' border='0'>
		</p>
	</body>
</html>
