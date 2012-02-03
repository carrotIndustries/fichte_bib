<?php
require("defs.php");

function mysql_fetch_superarray($result) {
	$out = array();
	while($row = mysql_fetch_assoc($result)) {
		array_push($out, $row);
	}
	return $out;
}

function array_getkeys($array) {
	$out = array();
	reset($array);
	$i = 0;
	while($i < count($array)) {
		//echo key($array);
		array_push($out, key($array));
		next($array);
		$i++;
	}
	return $out;
}

$link    = mysql_connect($dbsrv, $dbuser, $dbpasswd);
mysql_query("USE " . $dbname, $link);

function object_available($id) {
	//print_r(count(mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate != '0-0-0' AND object_id =" . $id, $link))));
	if(count(mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate = '0-0-0' AND object_id =" . $id))) == 0) {
		return true;
	}
	else {
		return false;
	}
}

function object_expired($id) {
	//print_r(count(mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate != '0-0-0' AND object_id =" . $id, $link))));
	if(count(mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_expiredate <= CURDATE() AND lend_returndate = '0-0-0' AND object_id =" . $id))) == 0) {
		return false;
	}
	else {
		return true;
	}
}

function getLocation($id) {
	$id = mysql_real_escape_string($id);
	$query ="SELECT location FROM locations WHERE id = " . $id;
	$result = mysql_fetch_superarray(mysql_query($query));
	return $result[0]["location"];
}

function getmediatype($id) {
	$id = mysql_real_escape_string($id);
	$query ="SELECT media FROM mediatypes WHERE id = " . $id;
	$result = mysql_fetch_superarray(mysql_query($query));
	return $result[0]["media"];
}
	
function getgroup($id) {
	$id = mysql_real_escape_string($id);
	$query ="SELECT name FROM groups WHERE id = " . $id;
	$result = mysql_fetch_superarray(mysql_query($query));
	return $result[0]["name"];
}


function listobjects($search_for, $match, $search, $limit, $mediatypes, $order_by, $order, $location, $sstate) {
	$search_for = mysql_real_escape_string($search_for);
	$search = mysql_real_escape_string($search);
	$order_by = mysql_real_escape_string($order_by);
	#$match = mysql_real_escape_string($match);
	$order = mysql_real_escape_string($order);
	$location = mysql_real_escape_string($location);
	$mediatypes = mysql_real_escape_string($mediatypes);
	$mediatypesa = array_slice(explode(",", $mediatypes), 0, -1);
	//print_r($mediatypesa);
	$query = "SELECT * FROM usercols";
	$cols = mysql_fetch_superarray(mysql_query($query));
	$usercols = "";
	foreach($cols as $col) {
		$usercols = $usercols . " ,user" . $col["id"] . " ";
	}
	//echo $usercols;
	$query ="SELECT objects.id as id, objects.media_type as obj_mt, author1_lastname, author1_firstname, author2_lastname, author2_firstname, title, isbn , publisher, year, edition, genre, deleted, objects.location as obj_loc, locations.id as loc_id, locations.location as loc_name, mediatypes.id as media_id, mediatypes.media as mt_name" . $usercols . " FROM objects, locations, mediatypes ";
	if($search != "") {
		if($match == "contains") {
			$query = $query . "WHERE " . $search_for . " LIKE '%" . $search . "%'";
		}
		if($match == "start") {
			$query = $query . "WHERE " . $search_for . " LIKE '" . $search . "%'";
		}
		if($match == "end") {
			$query = $query . "WHERE " . $search_for . " LIKE '%" . $search . "'";
		}
		if($match == "equal") {
			$query = $query . "WHERE " . $search_for . " LIKE '" . $search . "'";
		}
			
	}
	else {
		$search_for = "isbn";
		$search = "";
		$match ="contains";
	}
	
	if($sstate == "all" || $sstate == "avail") {
		if($limit == "all") {
			$limit_to = 20000;
		}
		if($limit == "l20") {
			$limit_to = 20;
		}
		if($limit == "l50") {
			$limit_to = 50;
		}
		if($limit == "l100") {
			$limit_to = 100;
		}
	}
	else {
		$limit_to = 20000;
	}
	if(strpos($mediatypes, ":1") !== FALSE) {
		if(strpos($query, "WHERE") === false) {
			$query = $query . "WHERE (";
		}
		else {
			$query = $query . "AND (";
		}
		foreach($mediatypesa as $mt) {
			$mta = explode(":", $mt);
			if($mta[1] == "1") {
				$query = $query . " media_type=" . $mta[0] . " OR";
			}
		}
		$query = substr($query, 0, -3);
		$query = $query . ")";
	}
	//echo $query;
	if($location != -1) {
		if(strpos($query, "WHERE") === false) {
			$query = $query . "WHERE (";
		}
		else {
			$query = $query . "AND (";
		}
		$query = $query . 'objects.location=' . $location;
		$query = $query . ")";
	}
	
	if(strpos($query, "WHERE") === false) {
		$query = $query . "WHERE (deleted=0)";
	}
	else {
		$query = $query . "AND (deleted=0)";
	}
	
	if(strpos($query, "WHERE") === false) {
		$query = $query . " WHERE (locations.id = objects.location)";
	}
	else {
		$query = $query . " AND (locations.id = objects.location)";
	}
	if(strpos($query, "WHERE") === false) {
		$query = $query . " WHERE (mediatypes.id = objects.media_type)";
	}
	else {
		$query = $query . " AND (mediatypes.id = objects.media_type)";
	}
	
	$query = $query . " ORDER BY " . $order_by . " " . $order . " LIMIT 0, " . $limit_to;
	//echo $query;
	$result = mysql_fetch_superarray(mysql_query($query));
	echo "[\n";
	foreach($result as $item) {
		$show = FALSE;
		$state = 0;
		if(!object_available($item["id"])) {
			$state = 1;
		}
		if(object_expired($item["id"])) {
			$state = 2;
		}
		#echo $state;
		switch($sstate) {
			case "all" :
				$show=TRUE;
			break;
			
			case "avail" :
				if($state == 0) {
					$show = TRUE;
				}
			break;
			
			case "lend" :
				if(($state == 1) || ($state ==2)) {
					$show = TRUE;
				}
			break;
			
			case "expired" :
				if($state ==2) {
					$show = TRUE;
				}
			break;
		}
		if($show) {
			$cols = array_getkeys($item);
			echo "{";
			foreach($cols as $column) {
				echo $column . ': "' . addslashes($item[$column]) . '", ';
			}
			$state = 0;
			if(!object_available($item["id"])) {
				$state = 1;
			}
			if(object_expired($item["id"])) {
				$state = 2;
			}
			echo 'state: ' . $state . ' ';
			
			echo "},\n";
		}
	}
	echo "]";
}
#listobjects("title", "contains", "", "all", false, false, false, false, "id", "asc");

function listlocations() {
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM locations ORDER BY location ASC"));
	echo "[\n";
	foreach($result as $item) {
		$cols = array_getkeys($item);
		echo "{";
		foreach($cols as $column) {
			echo $column . ': "' . $item[$column] . '", ';
		}
		echo "},\n";
	}
	echo "]";
}

function listgroups() {
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM groups ORDER BY name ASC"));
	echo "[\n";
	foreach($result as $item) {
		$cols = array_getkeys($item);
		echo "{";
		foreach($cols as $column) {
			echo $column . ': "' . $item[$column] . '", ';
		}
		echo "},\n";
	}
	echo "]";
}

function addobject($media_type, $author1_lastname, $author1_firstname, $author2_lastname, $author2_firstname, $title, $isbn, $publisher, $year, $edition, $genre, $location, $usercols) {
	$usercols = mysql_real_escape_string($usercols);
	$usercols = explode(";", $usercols);
	
	$media_type = mysql_real_escape_string($media_type);
	$author1_lastname = mysql_real_escape_string($author1_lastname);
	$author1_firstname = mysql_real_escape_string($author1_firstname);
	$author2_lastname = mysql_real_escape_string($author2_lastname);
	$author2_firstname = mysql_real_escape_string($author2_firstname);
	$title = mysql_real_escape_string($title);
	$isbn = mysql_real_escape_string($isbn);
	if(strlen($isbn) != 13) {
		echo "isbn";
		return;
	}
	$publisher = mysql_real_escape_string($publisher);
	$year = mysql_real_escape_string($year);
	$edition = mysql_real_escape_string($edition);
	$genre = mysql_real_escape_string($genre);
	$location = mysql_real_escape_string($location);
	if($title == "") {
		echo "missingtitle";
		return;
	}

	$result = mysql_fetch_assoc(mysql_query("SELECT max(id) FROM objects"));
	$id = $result['max(id)'] + 1;
	#$query = 'INSERT INTO objects VALUES (' . $id . ', "' . mysql_real_escape_string($_POST['media_type']) . '", "' . mysql_real_escape_string($_POST['author1_lastname']) . '", "' . mysql_real_escape_string($_POST['author1_firstname'])  . '", "' . mysql_real_escape_string($_POST['author2_lastname']) . '", "' . mysql_real_escape_string($_POST['author2_firstname']) . '", "' . mysql_real_escape_string($_POST['title']) . '", "' . mysql_real_escape_string($_POST['isbn']) . '", "' . mysql_real_escape_string($_POST['publisher']) . '", "' . mysql_real_escape_string($_POST['year']) . '", "' . mysql_real_escape_string($_POST['edition']) . '", "' . mysql_real_escape_string($_POST['genre']) . '", 0)';
	$query = 'INSERT INTO objects SET id=' . $id . ', media_type ="' . $media_type . '", author1_lastname="' . $author1_lastname . '", author1_firstname="' . $author1_firstname . '", author2_lastname="' . $author2_lastname . '", author2_firstname="' . $author2_firstname . '", title="' . $title . '", isbn="' . $isbn . '", publisher="' . $publisher . '", year="' . $year . '", edition="' . $edition . '", genre="' . $genre . '", location="' . $location . '", deleted = "0"';
	foreach ($usercols as $col) {
		$col = explode(":" , $col);
		if(count($col) > 1) {
			$query = $query . ", user" . $col[0] . "='" . $col[1] . "' ";
		}
	}
	//echo $query;
	mysql_query($query) or die("Fehler:" . mysql_error());
	echo "done";
}


function editobject($id, $media_type, $author1_lastname, $author1_firstname, $author2_lastname, $author2_firstname, $title, $isbn, $publisher, $year, $edition, $genre, $location, $usercols) {
	$usercols = mysql_real_escape_string($usercols);
	$usercols = explode(";", $usercols);
	$id = mysql_real_escape_string($id);
	$media_type = mysql_real_escape_string($media_type);
	$author1_lastname = mysql_real_escape_string($author1_lastname);
	$author1_firstname = mysql_real_escape_string($author1_firstname);
	$author2_lastname = mysql_real_escape_string($author2_lastname);
	$author2_firstname = mysql_real_escape_string($author2_firstname);
	$title = mysql_real_escape_string($title);
	$isbn = mysql_real_escape_string($isbn);
	if(strlen($isbn) != 13) {
		echo "isbn";
		return;
	}
	$publisher = mysql_real_escape_string($publisher);
	$year = mysql_real_escape_string($year);
	$edition = mysql_real_escape_string($edition);
	$genre = mysql_real_escape_string($genre);
	$location = mysql_real_escape_string($location);
	if($title == "") {
		echo "missingtitle";
		return;
	}

	#$query = 'INSERT INTO objects VALUES (' . $id . ', "' . mysql_real_escape_string($_POST['media_type']) . '", "' . mysql_real_escape_string($_POST['author1_lastname']) . '", "' . mysql_real_escape_string($_POST['author1_firstname'])  . '", "' . mysql_real_escape_string($_POST['author2_lastname']) . '", "' . mysql_real_escape_string($_POST['author2_firstname']) . '", "' . mysql_real_escape_string($_POST['title']) . '", "' . mysql_real_escape_string($_POST['isbn']) . '", "' . mysql_real_escape_string($_POST['publisher']) . '", "' . mysql_real_escape_string($_POST['year']) . '", "' . mysql_real_escape_string($_POST['edition']) . '", "' . mysql_real_escape_string($_POST['genre']) . '", 0)';
	$query = 'UPDATE objects SET media_type ="' . $media_type . '", author1_lastname="' . $author1_lastname . '", author1_firstname="' . $author1_firstname . '", author2_lastname="' . $author2_lastname . '", author2_firstname="' . $author2_firstname . '", title="' . $title . '", isbn="' . $isbn . '", publisher="' . $publisher . '", year="' . $year . '", edition="' . $edition . '", genre="' . $genre . '", location="' . $location . '", deleted = "0" ';
	#echo $query;
	foreach ($usercols as $col) {
		$col = explode(":" , $col);
		if(count($col) > 1) {
			$query = $query . ", user" . $col[0] . "='" . $col[1] . "' ";
		}
	}
	$query = $query . 'WHERE id=' . $id;
	//echo $query;
	mysql_query($query) or die("Fehler:" . mysql_error());
	echo "done";
}

function object_exist($isbn) {
	$query  = 'SELECT title FROM objects WHERE deleted = 0 AND isbn = ' . mysql_real_escape_string($isbn);
	$val = count(mysql_fetch_superarray(mysql_query($query)));
	echo $val;
	return $val;
	
}

function isbntoid($isbn) {
	$query  = 'SELECT id, title, author1_lastname, author1_firstname FROM objects WHERE deleted = 0 AND isbn = ' . mysql_real_escape_string($isbn);
	$result = mysql_fetch_superarray(mysql_query($query));
	echo "[\n";
	foreach($result as $item) {
		$cols = array_getkeys($item);
		echo "{";
		foreach($cols as $column) {
			echo $column . ': "' . $item[$column] . '", ';
		}
				
		echo "},\n";
	}
	echo "]";
		
	
}

function object_exist_id($id) {
	$query  = 'SELECT title FROM objects WHERE deleted = 0 AND id = ' . mysql_real_escape_string($id);
	return count(mysql_fetch_superarray(mysql_query($query)));
}

function location_exist($id) {
	$query  = 'SELECT * FROM locations WHERE id = ' . mysql_real_escape_string($id);
	return count(mysql_fetch_superarray(mysql_query($query)));
}

function group_exist($id) {
	$query  = 'SELECT * FROM groups WHERE id = ' . mysql_real_escape_string($id);
	return count(mysql_fetch_superarray(mysql_query($query)));
}



function deleteobject($id) {
	if(object_exist_id($id) == 1) {
		$id = mysql_real_escape_string($_GET["id"]);
		mysql_query("UPDATE objects SET deleted=1 WHERE id=" . $id) or die("Fehler:" . mysql_error());
		echo "done";
	}
	else {
		echo "nonexist";
	}
}

function addlocation($location) {
	$location = mysql_real_escape_string($location);
	if($location == "") {
		echo "empty";
		return;
	}
	$result = mysql_fetch_assoc(mysql_query("SELECT max(id) FROM locations"));
	$id = $result['max(id)'] + 1;
	$query = "INSERT INTO locations SET id=" . $id . ", location='" . $location . "'";
	mysql_query($query);
	echo "done";
}


function addgroup($name, $maxlend, $duration) {
	$name = mysql_real_escape_string($name);
	$maxlend = mysql_real_escape_string($maxlend);
	$duration = mysql_real_escape_string($duration);
	if($name == "") {
		echo "empty";
		return;
	}
	$result = mysql_fetch_assoc(mysql_query("SELECT max(id) FROM groups"));
	$id = $result['max(id)'] + 1;
	$query = "INSERT INTO groups SET id=" . $id . ", name='" . $name . "', maxlend=" . $maxlend . ", duration=" . $duration;
	mysql_query($query);
	echo "done";
}

function editgroup($id, $name, $maxlend, $duration) {
	$id = mysql_real_escape_string($id);
	$name = mysql_real_escape_string($name);
	$maxlend = mysql_real_escape_string($maxlend);
	$duration = mysql_real_escape_string($duration);
	if($name == "") {
		echo "empty";
		return;
	}
	$query = "UPDATE groups SET name='" . $name . "', maxlend=" . $maxlend . ", duration=" . $duration . " WHERE id=" . $id;
	mysql_query($query);
	echo "done";
}


function editlocation($id, $location) {
	$id = mysql_real_escape_string($id);
	$location = mysql_real_escape_string($location);
	if($location == "") {
		echo "empty";
		return;
	}
	$query = "UPDATE locations SET location='" . $location . "' WHERE id=" . $id;
	mysql_query($query);
	echo "done";
}

function deletelocation($id) {
	if(location_exist($id) == 1) {
		$id = mysql_real_escape_string($_GET["id"]);
		if($id != 0) {
			mysql_query("UPDATE objects SET location=0 WHERE location=" . $id) or die("Fehler:" . mysql_error());
			mysql_query("DELETE FROM locations WHERE id=" . $id) or die("Fehler:" . mysql_error());
			echo "done";
		}
		else {
			echo "heilig";
		}
	}
	else {
		echo "nonexist";
	}
}

function deletegroup($id) {
	if(group_exist($id) == 1) {
		$id = mysql_real_escape_string($_GET["id"]);
		
		if($id != 0) {
			//mysql_query("UPDATE objects SET location=0 WHERE location=" . $id) or die("Fehler:" . mysql_error());
			mysql_query("DELETE FROM groups WHERE id=" . $id) or die("Fehler:" . mysql_error());
			echo "done";
		}
		else {
			echo "heilig";
		}
	}
	else {
		echo "nonexist";
	}
}


function getobject($id) {
	$id = mysql_real_escape_string($id);
	$query = "SELECT * FROM objects WHERE id=" . $id;
	$result = mysql_fetch_assoc(mysql_query($query));
	$keys = array_getkeys($result);
	echo "[{";
	foreach($keys as $key) {
		echo $key  . ': "' . $result[$key] . '", ';
	}
	echo "}]";
}

/*function getlocation($id) {
	$id = mysql_real_escape_string($id);
	$query = "SELECT * FROM locations WHERE id=" + $id;
	$result = mysql_fetch_assoc(mysql_query($query));
	echo $result[0]["location"];
}*/

function addpupil($lastname, $firstname, $class, $card_id, $group) {
	$lastname = mysql_real_escape_string($lastname);
	$firstname = mysql_real_escape_string($firstname);
	$class = mysql_real_escape_string($class);
	$card_id = mysql_real_escape_string($card_id);
	$group = mysql_real_escape_string($group);
	if($lastname == "" || $firstname == "" || $lastname == "class" || $card_id == "") {
		echo "empty";
		return;
	}
	$result = mysql_fetch_assoc(mysql_query("SELECT max(id) FROM pupils"));
	$id = $result['max(id)'] + 1;
	$query = "INSERT INTO pupils SET id=" . $id . ", lastname='" . $lastname . "', firstname='" . $firstname . "', class='" . $class . "', card_id='" . $card_id . "', grp=" . $group . ", deleted = 0";
	mysql_query($query);
	echo "done";
}


function listpupils($search_for, $match, $search, $order_by, $order, $group) {
	$search_for = mysql_real_escape_string($search_for);
	$search = mysql_real_escape_string($search);
	$order_by = mysql_real_escape_string($order_by);
	#$match = mysql_real_escape_string($match);
	$order = mysql_real_escape_string($order);
	$group = mysql_real_escape_string($group);
	$query ="SELECT pupils.id as id, lastname, firstname, class, deleted, card_id, grp, groups.id as grp_id, groups.name as grp_name FROM pupils,groups ";
	if($search != "") {
		if($match == "contains") {
			$query = $query . "WHERE " . $search_for . " LIKE '%" . $search . "%'";
		}
		if($match == "start") {
			$query = $query . "WHERE " . $search_for . " LIKE '" . $search . "%'";
		}
		if($match == "end") {
			$query = $query . "WHERE " . $search_for . " LIKE '%" . $search . "'";
		}
		if($match == "equal") {
			$query = $query . "WHERE " . $search_for . " LIKE '" . $search . "'";
		}
			
	}
	else {
		$search_for = "card_id";
		$search = "";
		$match ="contains";
	}
				
	if($group != -1) {
		if(strpos($query, "WHERE") === false) {
			$query = $query . " WHERE (";
		}
		else {
			$query = $query . "AND (";
		}
		$query = $query . 'grp=' . $group;
		$query = $query . ")";
	}
	
	if(strpos($query, "WHERE") === false) {
		$query = $query . " WHERE (groups.id = pupils.grp)";
	}
	else {
		$query = $query . " AND (groups.id = pupils.grp)";
	}
	
	$query = $query . " ORDER BY " . $order_by . " " . $order;
	$result = mysql_fetch_superarray(mysql_query($query));
	echo "[\n";
	foreach($result as $item) {
		$cols = array_getkeys($item);
		echo "{";
		foreach($cols as $column) {
			echo $column . ': "' . $item[$column] . '", ';
			
		}
		echo 'can_lend: ' . can_lend($item["id"]);
		echo "},\n";
	}
	echo "]";
}

function pupil_exist_id($id) {
	$query  = 'SELECT * FROM pupils WHERE deleted = 0 AND id = ' . mysql_real_escape_string($id);
	return count(mysql_fetch_superarray(mysql_query($query)));
}

function pupil_exist_card($card_id) {
	$query  = 'SELECT * FROM pupils WHERE deleted = 0 AND card_id = ' . mysql_real_escape_string($card_id);
	return count(mysql_fetch_superarray(mysql_query($query)));
}

function deletepupil($id) {
	if(pupil_exist_id($id) == 1) {
		$id = mysql_real_escape_string($_GET["id"]);
		mysql_query("UPDATE pupils SET deleted=1 WHERE id=" . $id) or die("Fehler:" . mysql_error());
		echo "done";
	}
	else {
		echo "nonexist";
	}
}

function editpupil($id, $lastname, $firstname, $class, $card_id, $group) {
	$id = mysql_real_escape_string($id);
	$lastname = mysql_real_escape_string($lastname);
	$firstname = mysql_real_escape_string($firstname);
	$class = mysql_real_escape_string($class);
	$card_id = mysql_real_escape_string($card_id);
	$group = mysql_real_escape_string($group);
	if($lastname == "" || $firstname == "" || $lastname == "class" || $card_id == "") {
		echo "empty";
		return;
	}
	$query = "UPDATE pupils SET lastname='" . $lastname . "', firstname='" . $firstname . "', class='" . $class . "', card_id='" . $card_id . "', grp=" . $group . ", deleted = 0 WHERE id=" . $id;
	mysql_query($query);
	echo "done";
}

function can_lend($id) {
	$id = mysql_real_escape_string($id);
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate='0-0-0' AND pupil_id=" . $id));
	$lended_objects = count($result);
	
	$result = mysql_fetch_superarray(mysql_query("SELECT grp FROM pupils WHERE id=" . $id));
	$group = $result[0]["grp"];
	$result = mysql_fetch_superarray(mysql_query("SELECT maxlend FROM groups WHERE id=" . $group));
	$maxlend = $result[0]["maxlend"];
	
	if($lended_objects < $maxlend) {
		return 1;
	}
	else {
		return 0;
	}
}

function lend($pupil, $params) {
	$pupil = mysql_real_escape_string($pupil);
	$objects = explode(";", $params);
	$objects = array_slice($objects, 0, -1);
	
	$year = date("Y", time());
	$month = date("n", time());
	$day = date("j", time());
	foreach($objects as $object) {
		$data = explode(",", $object);
		mysql_query("INSERT INTO lend SET pupil_id=" . $pupil . ", object_id=" . $data[0] . ", lend_date='" . $year . "-" . $month . "-" . $day . "', lend_expiredate='" . $data[1] . "-" . $data[2] . "-" . $data[3] . "', lend_returndate='0-0-0', printed=1");
	}
	echo "done";
}

function returnobject($id) {
	$id = mysql_real_escape_string($id);
	
	$query = "SELECT * FROM lend WHERE lend_expiredate <= CURDATE() AND lend_returndate = '0-0-0' AND object_id=" . $id;
	if(count(mysql_fetch_superarray(mysql_query($query))) > 0) {
		echo "exp";
	}
	
	$dates = getdate();
	$year = $dates["year"];
	$month = $dates["mon"];
	$day = $dates["mday"];
	$query  = 'UPDATE lend SET lend_returndate="' . $year . '-' . $month . '-' . $day . '" WHERE object_id = ' . $id . ' AND lend_returndate="0-0-0"';
	mysql_query($query);
	echo "done";
}

function longerobject($id, $days) {
	$id = mysql_real_escape_string($id);
	$days = mysql_real_escape_string($days);
	$query = 'UPDATE lend SET lend_expiredate=ADDDATE(lend_expiredate, INTERVAL ' . $days .  ' DAY)  WHERE object_id = ' . $id . ' AND lend_returndate="0-0-0"';
	mysql_query($query);
	echo "done";
}

function convertdate($indate) {
	return implode(".", array_reverse(explode("-", $indate)));
}

function pupilid($card_id) {
	$card_id = mysql_real_escape_string($card_id);
	$query = 'SELECT id FROM pupils WHERE deleted = 0 AND card_id="'. $card_id . '"';
	$result = mysql_fetch_superarray(mysql_query($query));
	if(count($result) == 0) {
		echo "nonexist";
		return;
	}
	echo $result[0]["id"];
	
}	

function can_be_lend($id) {
	$id = mysql_real_escape_string($id);
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate='0-0-0' AND object_id=" . $id));

	
	
	if(count($result) == 0) {
		return 1;
	}
	else {
		return 0;
	}
}

function wholend($id) {
	$id = mysql_real_escape_string($id);
	$query = "SELECT lastname, firstname, lend_expiredate FROM lend, pupils WHERE lend.object_id =" . $id . " AND lend.pupil_id = pupils.id AND lend_returndate = '0-0-0'";
	$result = mysql_fetch_assoc(mysql_query($query));
	$keys = array_getkeys($result);
	echo "[{";
	foreach($keys as $key) {
		if($key != "lend_expiredate") {
			echo $key  . ': "' . $result[$key] . '", ';
		}
		else {
			echo $key  . ': "' . convertdate($result[$key]) . '", ';
		}
	}
	echo "}]";
}

function advanceprinted($id) {
	$id = mysql_real_escape_string($id);
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate = '0-0-0' AND object_id =" . $id));
	$printed =  $result[0]["printed"]+1;
	$query = "UPDATE lend SET printed=" . $printed . " WHERE lend_returndate = '0-0-0' AND object_id =" . $id;
	mysql_query($query);
	echo "done";
}

function generatecode($prefix) {
	$query = "SELECT code FROM meta";
	$result = mysql_fetch_assoc(mysql_query($query));
	$code = $result["code"];
	$query = "UPDATE meta SET code=" . ($code+1);
	mysql_query($query);
	
	
		if (strlen($prefix) != 4) {
			die("fail");
		}
		$code = $prefix . $code;
		$oddeven = 1;
		for ($i = 1; $i <= 12; $i++)
		{
			$num = substr($code, $i-1, 1);
			if ($oddeven == 1)
			{
			$intsum = $num * $oddeven;
			@$extsum += $intsum;
			$oddeven = 3;
			}
			else
			{
			$intsum = $num * $oddeven;
			$extsum = $extsum + $intsum;
			$oddeven = 1;
			}
		}

		$check = (floor($extsum/10)*10+10) - $extsum;

		if ($check == 10)
		{
			$check = 0;
		}
		$code = $code . $check;
		return $code;
	
	//echo $query;
}

function get_display_cols() {
	$query = "SELECT display_cols FROM meta";
	$result = mysql_fetch_assoc(mysql_query($query));
	$cols = $result["display_cols"];
	echo "[";
	$i = 0;
	while($i < strlen($cols)) {
		
		echo $cols{$i}==0 ? "false": "true";
		echo ",";
		$i++;
	}
	echo "]";
}

function get_user_cols() {
	$query = "SELECT * FROM usercols";
	$result = mysql_fetch_superarray(mysql_query($query));
	echo "[\n";
	foreach($result as $item) {
		$cols = array_getkeys($item);
		echo "{";
		foreach($cols as $column) {
			echo $column . ': "' . $item[$column] . '", ';
			
		}
		//echo 'can_lend: ' . can_lend($item["id"]);
		echo "},\n";
	}
	echo "]";
	/*$cols = $result["display_cols"];
	echo "[";
	$i = 0;
	while($i < strlen($cols)) {
		
		echo $cols{$i}==0 ? "false": "true";
		echo ",";
		$i++;
	}
	echo "]";*/
}

function add_user_col($name) {
	$name = mysql_real_escape_string($name);
	$name = str_replace(";", "", $name);
	$name = str_replace(":", "", $name);
	$result = mysql_fetch_assoc(mysql_query("SELECT max(id) FROM usercols"));
	$id = $result['max(id)'] + 1;
	$query = "INSERT INTO usercols SET id=" . $id . " , name='" . $name . "'";
	mysql_query($query);
	$query = "ALTER TABLE objects ADD COLUMN (user" . $id . " char(30))";
	mysql_query($query);
	/*$query = "SELECT * FROM meta";
	$result = mysql_fetch_assoc(mysql_query($query));
	$cols = array_getkeys($result);
	$max = 0;
	//echo "[";
	foreach($cols as $col) {
		if(strpos($col, "user") === 0) {
			if (intval(substr($col, 4))>$max) {
				$max=intval(substr($col, 4));
			}
		}
	}
	//echo $max;
	$query = "ALTER TABLE meta ADD COLUMN (user" . ($max+1) . " char(30))";
	//echo $query;
	mysql_query($query);
	$query = "UPDATE meta SET user" . ($max+1) . " ='" . $name . "'";
	mysql_query($query);*/
	echo "done";
	
}

function delete_user_col($id) {
	$id = mysql_real_escape_string($id);
	$query = "DELETE FROM usercols WHERE id=" . $id;
	mysql_query($query);
	$query = "ALTER TABLE objects DROP COLUMN user" . $id;
	mysql_query($query);
	echo "done";
}

function edit_user_col($id, $name) {
	$id = mysql_real_escape_string($id);
	$name = mysql_real_escape_string($name);
	$name = str_replace(";", "", $name);
	$name = str_replace(":", "", $name);
	$query = "UPDATE usercols SET name ='" . $name . "' WHERE id=" . $id;
	mysql_query($query);
	echo "done";
}

function set_display_cols($cols) {
	$cols = mysql_real_escape_string($cols);
	$query = "UPDATE meta SET display_cols='" . $cols . "'";
	//echo $query;
	mysql_query($query);
	echo "done";

}

function get_remindtext() {
	$query = "SELECT remindtext FROM meta";
	$result = mysql_fetch_assoc(mysql_query($query));
	return $result["remindtext"];
}

function set_remindtext($text) {
	$text = mysql_real_escape_string($text);
	$query = 'UPDATE meta SET remindtext = "' . $text . '"';
	mysql_query($query);
	echo "done";
}

function get_fine() {
	$query = "SELECT fine FROM meta";
	$result = mysql_fetch_assoc(mysql_query($query));
	return $result["fine"];
}

function set_fine($val) {
	$val = mysql_real_escape_string($val);
	$query = 'UPDATE meta SET fine = ' . $val;
	mysql_query($query);
	echo "done";
}

function get_formdefaults() {
	$query = "SELECT formdefaults FROM meta";
	$result = mysql_fetch_assoc(mysql_query($query));
	return explode(",", $result["formdefaults"]);
}

function set_formdefaults($val) {
	$val = mysql_real_escape_string($val);
	$query = 'UPDATE meta SET formdefaults = "' . $val . '"';
	mysql_query($query);
	echo "done";
}

function set_latest($date, $mode) {
	$date = mysql_real_escape_string($date);
	$mode = mysql_real_escape_string($mode);
	$query = 'UPDATE meta SET last_return = "' . $date . '", last_return_mode="' . $mode . '"';
	#echo $query;
	mysql_query($query);
	echo "done";
}

function addmediatype($mt) {
	$mt = mysql_real_escape_string($mt);
	if($mt == "") {
		echo "empty";
		return;
	}
	$result = mysql_fetch_assoc(mysql_query("SELECT max(id) FROM mediatypes"));
	$id = $result['max(id)'] + 1;
	$query = "INSERT INTO mediatypes SET id=" . $id . ", media='" . $mt . "'";
	mysql_query($query);
	echo "done";
}

function editmediatype($id, $mt) {
	$id = mysql_real_escape_string($id);
	$mt = mysql_real_escape_string($mt);
	if($mt == "") {
		echo "empty";
		return;
	}
	$query = "UPDATE mediatypes SET media='" . $mt . "' WHERE id=" . $id;
	mysql_query($query);
	echo "done";
}

function deletemediatype($id) {
	$id = mysql_real_escape_string($_GET["id"]);
	if($id != 0) {
		mysql_query("UPDATE objects SET media_type=0 WHERE media_type=" . $id) or die("Fehler:" . mysql_error());
		mysql_query("DELETE FROM mediatypes WHERE id=" . $id) or die("Fehler:" . mysql_error());
		echo "done";
	}
	else {
		echo "heilig";
	}
}

function listmediatypes() {
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM mediatypes ORDER BY media ASC"));
	echo "[\n";
	foreach($result as $item) {
		$cols = array_getkeys($item);
		echo "{";
		foreach($cols as $column) {
			echo $column . ': "' . addslashes($item[$column]) . '", ';
		}
		echo "},\n";
	}
	echo "]";
}

function getmediatypes() {
	return mysql_fetch_superarray(mysql_query("SELECT * FROM mediatypes ORDER BY media ASC"));
}

function get_lpropts() {
	$query = "SELECT lpropts FROM meta";
	$result = mysql_fetch_assoc(mysql_query($query));
	return $result["lpropts"];
}

function set_lpropts($val) {
	$val = mysql_real_escape_string($val);
	$query = 'UPDATE meta SET lpropts = "' . $val. '"';
	mysql_query($query);
	echo "done";
}

?>
