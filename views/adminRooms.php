<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Raumverwaltung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bildschirm.css" rel="stylesheet" media="all">
</head>
<body>
<div class="wrapper">
	<nav><?=  $link ?></nav>

	<div class="allrooms">
    <p id="meldung"><?= $meldung ?></p>
		<h3 id="allrooms">Raumübersicht alle "grüne"  Räume </h3><br>
    <?php
    if ($_SESSION['rights'] == "gast") {
      echo "<h4 class=\"h4gast\">Hier können Sie sich alle \"grüne\"  Räume im BFW  ansehen und nach einem freien Raum suchen. Wenn Sie einen Raum buchen möchten, bitte melden Sie sich im Büro . </h4><br>";
    }
    ?>

		<p class="anlegen"><?= $anlegen ?></p>
		<br>
    <?php
		$x="";
		echo '<table>
			<tr>
				<th>ID</th>
				<th>RaumNr</th>
				<th>Gebäude</th>
				<th>Etage</th>
				<th>Personen</th>
				<th>Art</th>
				<th>Ausstatung</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>';
			$roomList = $roomDAO->readAll();
			for ($i=0; $i < count($roomList); $i++) {
				$x = ($x=="#ccc") ? "#eee" : "#ccc";

        $urlParams1 = '?id=' . $roomList[$i]->getID();
        $urlParams2 = '?befehl=modifyRoom&rid=' . $roomList[$i]->getID() .
                      '&roomnr=' . $roomList[$i]->getNumber() .
                      '&floor=' .$roomList[$i]->getFloor() .
                      '&building=' . $roomList[$i]->getBuilding() .
                      '&personen=' . $roomList[$i]->getPersonen() .
                      '&art=' . $roomList[$i]->getArt() .
                      '&equipment=' . $roomList[$i]->getEquipment();
        $urlParams3 = '?befehl=deleteRoomFormular&rid=' . $roomList[$i]->getID() .
                      '&roomnr=' . $roomList[$i]->getNumber() .
                      '&floor='.$roomList[$i]->getFloor() .
                      '&building='.$roomList[$i]->getBuilding() .
                      '&personen='.$roomList[$i]->getPersonen() .
                      '&art='.$roomList[$i]->getArt() .
                      '&equipment=' . $roomList[$i]->getEquipment();

				echo '<tr style="background-color: ' . $x .'; ">
				<td>' . $roomList[$i]->getID() . '</td>
				<td>' . $roomList[$i]->getNumber() . '</td>
        <td>' . $roomList[$i]->getBuilding() . '</td>
        <td>' . $roomList[$i]->getFloor() . '</td>
        <td>' . $roomList[$i]->getPersonen() . '</td>
        <td>' . $roomList[$i]->getArt() . '</td>
			  <td>' . $roomList[$i]->getEquipment() . '</td>';

        echo '<td><a href="app/LEVcheckBookingPossibilities.html' . $urlParams1 . '" class=" button table" >Verfügbarkeit prüfen</a></td>';

        if ($_SESSION['rights'] == "administrator") {
          echo '<td><a href="index.php' . $urlParams2 . '" class="button table"> Ändern </a></td>
                <td><a href="index.php' . $urlParams3 . '" class="button table"> Löschen </a></td>';
        }

        echo '</tr>';
		  }

			echo "</table>";?>
		<br>

		<a href="#" onclick="myfun()" class="button table" >Drücken</a>

		<script type="text/javascript"> function myfun(){ window.print();}</script>

	</div>
		<footer role="contentinfo">
			<small>Copyright &copy; <time datetime="2020">2020</time> IT-Solution & Design GmbH | Tel: 040-123456 | E-Mail: raumverwaltung@it-solution.de</small>
		</footer>
	</div>
	</body>
</html>
