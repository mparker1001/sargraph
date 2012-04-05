<?php

ini_set('display_errors', 0);

Require '../phplot/phplot.php';

$logpath = '/var/log/sa/parsed/';

$cores = shell_exec("cat /proc/cpuinfo | grep processor | tail -1 | awk '{print $3}'") + 1;

$firstrun = 1;


switch ( $_GET['src'] ) {
	case "ram":
		$logfile = 'ramlog';
		$title = 'Physical RAM and Swap Usage';
		$ymax = '100';
		break;
	case "load":
		$logfile = 'loadlog';
                $title = 'Load Averages';
		$ymax = $cores * 2;
		break;
	case "cpu":
		$logfile = 'cpulog';
                $title = 'Overall CPU and Disk I/O Usage';
		$ymax = '100';
		break;
	default:
		die('Error');
}

switch ( $_GET['range'] ) {
	case 'all':
		$ght='500';
                $gwd='1500';
		$xincr = '86400';
                break;
        case 'day':
                $ght='500';
                $gwd='1000';
                $xincr = '3600';
                break;
	default:
		die('Error');
}

$filestr = $logpath . $logfile;

$row = 1;

$dateregex = '@^(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4}) (?P<hour>\d{2}):(?P<minute>\d{2})$@';

$dayrange = time() - 86400;

switch ( $_GET['src'] ) {
        case 'ram':
		if (($handle = fopen($filestr, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$row++;
				for ($c=0; $c < $num; $c++) {
			                if ( $c == 0 ) {
			                        $timestamp = $data[$c];
			                }
			                if ( $c == 1 ) {
			                        $timestamp .= ' '.$data[$c];
					}
			                if ( $c == 2 ) {
			                        $mempct = $data[$c];
			                }
                                        if ( $c == 3 ) {
                                                $swappct = $data[$c];
                                        }
				}
				preg_match($dateregex, $timestamp, $dateInfo);
				$unixTimestamp = mktime($dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'], $dateInfo['month'], $dateInfo['day'], $dateInfo['year']);
                                if ( $firstrun == '1') {
                                        $anchor = mktime($dateInfo['hour'], 0, 0, $dateInfo['month'], $dateInfo['day'], $dateInfo['year']);
                                        $firstrun = 0;
                                }
				if ( $_GET['range'] == 'day' ) {
					if ( $unixTimestamp > $dayrange ) $graphdata[] = array('',$unixTimestamp,$mempct,$swappct);
				}
				else {
					$graphdata[] = array('',$unixTimestamp,$mempct,$swappct);
				}
			}
			fclose($handle);
		}
		$plot = new PHPlot($gwd,$ght);
		$plot->SetLegend(array('Percent RAM Usage','Percent Swap Usage'));
                $plot->SetDataColors(array('blue','red'));
		$plot->SetYTickIncrement(10);
                break;
        case 'load':
                if (($handle = fopen($filestr, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $num = count($data);
                                $row++;
                                for ($c=0; $c < $num; $c++) {
                                        if ( $c == 0 ) {
                                                $timestamp = $data[$c];
                                        }
                                        if ( $c == 1 ) {
                                                $timestamp .= ' '.$data[$c];
                                        }
                                        if ( $c == 2 ) {
                                                $load1 = $data[$c];
                                        }
                                        if ( $c == 3 ) {
                                                $load2 = $data[$c];
                                        }
                                        if ( $c == 4 ) {
                                                $load3 = $data[$c];
                                        }
                                }
                                preg_match($dateregex, $timestamp, $dateInfo);
                                $unixTimestamp = mktime($dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'], $dateInfo['month'], $dateInfo['day'], $dateInfo['year']);
				if ( $firstrun == '1') {
					$anchor = mktime($dateInfo['hour'], 0, 0, $dateInfo['month'], $dateInfo['day'], $dateInfo['year']);
					$firstrun = 0;
				}
                                if ( $_GET['range'] == 'day' ) {
                                        if ( $unixTimestamp > $dayrange ) {
						$graphdata[] = array('',$unixTimestamp, $load1, $load2, $load3, $cores);
					}
                                }
                                else {
                                        $graphdata[] = array('',$unixTimestamp, $load1, $load2, $load3, $cores);
                                }
			}
                        fclose($handle);
                }
                $plot = new PHPlot($gwd,$ght);
                $plot->SetLegend(array('1 min avg','5 min avg','15 min avg','Max Load Threshold'));
		$plot->SetDataColors(array('blue', 'green', 'orange', 'red'));
		$plot->SetYTickIncrement(1);
                break;
        case 'cpu':
                if (($handle = fopen($filestr, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $num = count($data);
                                $row++;
                                for ($c=0; $c < $num; $c++) {
                                        if ( $c == 0 ) {
                                                $timestamp = $data[$c];
                                        }
                                        if ( $c == 1 ) {
                                                $timestamp .= ' '.$data[$c];
                                        }
                                        if ( $c == 2 ) {
                                                $cpupct = $data[$c];
                                        }
					if ( $c == 3 ) {
						$diskio = $data[$c];
					}
                                }
                                preg_match($dateregex, $timestamp, $dateInfo);
                                $unixTimestamp = mktime($dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'], $dateInfo['month'], $dateInfo['day'], $dateInfo['year']);
                                if ( $firstrun == '1') {
                                        $anchor = mktime($dateInfo['hour'], 0, 0, $dateInfo['month'], $dateInfo['day'], $dateInfo['year']);
                                        $firstrun = 0;
                                }
                                if ( $_GET['range'] == 'day' ) {
                                        if ( $unixTimestamp > $dayrange ) $graphdata[] = array('',$unixTimestamp,$cpupct,$diskio);
                                }
                                else {
                                        $graphdata[] = array('',$unixTimestamp,$cpupct,$diskio);
                                }
                        }
                        fclose($handle);
                }
                $plot = new PHPlot($gwd,$ght);
                $plot->SetLegend(array('Percent CPU Usage','Percent Disk I/O Usage'));
		$plot->SetDataColors(array('blue','green'));
		$plot->SetYTickIncrement(10);
                break;
        default:
                die('Error');
}

$plot->SetTitle($title);
$plot->SetDataValues($graphdata);
$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetXLabelAngle(90);
$plot->SetXTickLabelPos('xaxis');
$plot->SetXTickIncrement($xincr);
$plot->SetXGridLabelType("time");
$plot->SetXTimeFormat('%Y-%m-%d %H:%M');
$plot->SetDrawXGrid(True);
$plot->SetPlotAreaWorld(NULL, 0, NULL, $ymax);
$plot->SetXTickAnchor($anchor);

$plot->DrawGraph();

?>
