<?php
	// Debug mód
	error_reporting(E_ALL);
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);

	// MySQL csatlakozás
	$conn = NULL;

	include('config.php');

	$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

	if($conn->connect_error) {
		die("Nem lehet csatlakozni az adatabazishoz: " . $conn->connect_error);
 	}

 	$conn->set_charset("utf-8");

 	// MySQL-hez kapcsolódó függvények
	function e($_str) {
		$str = $conn->escape_string($_str);
		return $str;
	}

	// Turfökhöz kapcsolódó függvények
	function loadTurfs() {
		global $conn, $map_width, $fontsize;

		// Kiválasztjuk az összes létező bandát

		$sql = "SELECT * FROM `gangs`";
		$result = $conn->query($sql);

		if($result->num_rows > 0) {
			echo '<svg id="_map" class="dragger">';

			while($gang = $result->fetch_assoc()) {
				//echo "id: " . $gang["id"]. " - Name: " . $gang["name"] . "<br>";

				$gname = $gang["name"];
				$fillcolor = $gang["fillcolor"];
				$gangurl = $gang["thread"];

				// Kiválasztjuk az ADOTT bandához tartozó turföket
				$sql_ = "SELECT * FROM `turfs` WHERE `gangid` = " . $gang["id"];
				$result_ = $conn->query($sql_);
				while($turf = $result_->fetch_assoc()) {

					//echo "->turf id: " . $turf["id"] . "<br>";

					// Létrehozzok az SVG pontjait tartalmazó stringet, valamint egy-egy tömböt az X és Y koordinátáknak
					$points = "";
					$xarray = array();
					$yarray = array();

					// Kiválasztjuk az ADOTT turfhöz tartozó pontokat
					$sql__ = "SELECT * FROM `points` WHERE `turfid` = " . $turf["id"];
					$result__ = $conn->query($sql__);
					while($point = $result__->fetch_assoc()) {
						//echo "-->point id: " . $point["id"] . " x: " . $point["x"] . " y: " . $point["y"] . "<br>";

						$ratio = 6000 / $map_width;

						$x = ($point["x"] + 3000) / $ratio;
						$y = (3000 - $point["y"]) / $ratio;

						$points .= $x . "," . $y . " ";

						array_push($xarray, $x);
						array_push($yarray, $y);
					}

					// Kiszámoljuk a szöveg kívánt helyét, olyan módon hogy az X ek és Y-ok számtani közepét vesszük. Háromszögnél működik a legjobban, ekkor a súlypontot kapjuk meg. Jó, ha az alakzatunk konvex (xd).

					$textx = array_sum($xarray) / count($xarray);
					$texty = array_sum($yarray) / count($yarray);

					// Kiíratjuk az svg poligonokat :-)

					echo
						'<a href="'.$gangurl.'" class="svg">' .
						'<g>' . 
						'<polygon points="' . $points . '" style="fill:#'.$fillcolor.';stroke:#000;stroke-width:2;opacity:0.3;" />' .
						'<text x="'.$textx.'" y="'.$texty.'" font-family="Verdana" font-size="'.$fontsize.'" fill="white" text-anchor="middle" text-transform="uppercase">' . $gname . '<text>' .
						'</g>' .
						'</a>'
					;
				}

				//echo "<br><br>";
			}

			echo "</svg>";
		} else {
			echo "Nincsenek bandák az adatbázisban.";
		}
	}

	// Turf lista betöltése

	function loadTurfList() {

		echo '<ul id="factionlist">';

		global $conn, $map_width;

		$sql = "SELECT * FROM `gangs`";
		$result = $conn->query($sql);

		if($result->num_rows > 0) {
			while($gang = $result->fetch_assoc()) {
				echo 
					'<li class="faction"><div class="factsquare" style="background-color: #'.$gang["fillcolor"].'"></div><span class="factname"><a>'.$gang["name"].'</a></span>'
				;

				$sql_= "SELECT * FROM `turfs` WHERE `gangid` = ". $gang["id"];
				$result_ = $conn->query($sql_);

				$num = 1;

				if($result_->num_rows > 0) {
					echo '<ul class="turf">';

					while($turf = $result_->fetch_assoc()) {
						// Létrehozzok az SVG pontjait tartalmazó stringet, valamint egy-egy tömböt az X és Y koordinátáknak
						$points = "";
						$xarray = array();
						$yarray = array();

						// Kiválasztjuk az ADOTT turfhöz tartozó pontokat
						$sql__ = "SELECT * FROM `points` WHERE `turfid` = " . $turf["id"];
						$result__ = $conn->query($sql__);
						while($point = $result__->fetch_assoc()) {
							//echo "-->point id: " . $point["id"] . " x: " . $point["x"] . " y: " . $point["y"] . "<br>";

							$ratio = 6000 / $map_width;

							$x = ($point["x"] + 3000) / $ratio;
							$y = (3000 - $point["y"]) / $ratio;

							$points .= $x . "," . $y . " ";

							array_push($xarray, $x);
							array_push($yarray, $y);
						}

						// Kiszámoljuk a szöveg kívánt helyét, olyan módon hogy az X ek és Y-ok számtani közepét vesszük. Háromszögnél működik a legjobban, ekkor a súlypontot kapjuk meg. Jó, ha az alakzatunk konvex (xd).

						$center_x = array_sum($xarray) / count($xarray);
						$center_y = array_sum($yarray) / count($yarray);

						echo '<li><a onclick="scrMapTo('.$center_x.','.$center_y.')">&gt; Terület #'.$num.'</a></li>';	
						$num++;	
					}

					echo '<li><a href="'.$gang["thread"].'" target="_blank">&gt;&gt; Frakció topik</a></li>';
					echo '</ul>';

				}

				echo '</li>';

			}
		}

		echo '</ul>';
	}
?>