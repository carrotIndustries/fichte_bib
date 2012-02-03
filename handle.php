<?php
require("functions.php");
$action = $_GET["do"];
switch($action) {
	case "listobjects" :
		listobjects($_GET["search_for"],  $_GET["match"],  $_GET["search"],  $_GET["limit"],  $_GET["mediatypes"],  $_GET["order_by"],  $_GET["order"], $_GET["location"], $_GET["state"]);
	break;
	
	case "addobject" :
		addobject($_GET["media_type"], $_GET["author1_lastname"], $_GET["author1_firstname"], $_GET["author2_lastname"], $_GET["author2_firstname"], $_GET["title"], $_GET["isbn"], $_GET["publisher"], $_GET["year"], $_GET["edition"], $_GET["genre"], $_GET["location"], $_GET["usercols"]);
	break;
	
	case "editobject" :
		editobject($_GET["id"], $_GET["media_type"], $_GET["author1_lastname"], $_GET["author1_firstname"], $_GET["author2_lastname"], $_GET["author2_firstname"], $_GET["title"], $_GET["isbn"], $_GET["publisher"], $_GET["year"], $_GET["edition"], $_GET["genre"], $_GET["location"], $_GET["usercols"]);
	break;
	
	case "getlocation" :
		echo getLocation($_GET["id"]);
	break;
	
	case "getgroup" :
		echo getgroup($_GET["id"]);
	break;
	
	case "generate":
		$prefix = $_GET["prefix"];
		echo generatecode($prefix);
	break;
	
	case "makecode":
		$prefix = $_GET["prefix"];
		if (strlen($prefix) != 4) {
			die("fail");
		}
		$code = $prefix . $_GET["code"];
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
		echo $code;
	break;
	
	case "fetch":
		header("Content-Type: text/xml");
		readfile("http://books.google.com/books/feeds/volumes?q=" . $_GET["url"]);
	break;
	
	case "exist":
		echo object_exist($_GET["isbn"]);
	break;
	
	case "pupilexist":
		echo pupil_exist_id(intval($_GET["id"]));
	break;
	
	case "delete":
		echo deleteobject($_GET["id"]);
	break;
	
	case "getobject":
		echo getobject($_GET["id"]);
	break;
	
	case "getmediatype":
		echo getmediatype($_GET["id"]);
	break;
	
	case "addlocation":
		addlocation($_GET["location"]);
	break;
	
	case "addmediatype":
		addmediatype($_GET["mt"]);
	break;
	
	case "addgroup":
		addgroup($_GET["name"], $_GET["maxlend"], $_GET["duration"]);
	break;
	
	case "editgroup":
		editgroup($_GET["id"], $_GET["name"], $_GET["maxlend"], $_GET["duration"]);
	break;
	
	case "editlocation":
		editlocation($_GET["id"], $_GET["location"]);
	break;
	
	case "editmediatype":
		editmediatype($_GET["id"], $_GET["mt"]);
	break;
	
	case "listlocations" :
		listlocations();
	break;
	
	case "listmediatypes" :
		listmediatypes();
	break;
	
	case "listgroups" :
		listgroups();
	break;
	
	case "deletelocation" :
		deletelocation($_GET["id"]);
	break;

	case "deletemediatype" :
		deletemediatype($_GET["id"]);
	break;

	case "deletegroup" :
		deletegroup($_GET["id"]);
	break;
	
	case "addpupil" :
		addpupil($_GET["lastname"], $_GET["firstname"], $_GET["class"], $_GET["card_id"], $_GET["group"]);
	break;
	
	case "listpupils" :
		listpupils($_GET["search_for"], $_GET["match"], $_GET["search"], $_GET["order_by"], $_GET["order"], $_GET["group"]);
	break;
	
	case "deletepupil":
		deletepupil($_GET["id"]);
	break;
	
	case "editpupil" :
		editpupil($_GET["id"], $_GET["lastname"], $_GET["firstname"], $_GET["class"], $_GET["card_id"], $_GET["group"]);
	break;
	
	case "canlend" :
		echo can_lend($_GET["id"]);
	break;
	
	case "lend" :
		lend($_GET["pupil"], $_GET["param"]);
	break;
	
	case "return" :
		returnobject($_GET["id"]);
	break;
	
	case "longer" :
		longerobject($_GET["id"], $_GET["days"]);
	break;
	
	case "pupilid" :
		pupilid($_GET["card_id"]);
	break;
	
	case "isbntoid" :
		isbntoid($_GET["isbn"]);
	break;
	
	case "canbelend" :
		echo can_be_lend($_GET["id"]);
	break;
	
	case "wholend" :
		wholend($_GET["id"]);
	break;
	
	case "advanceprinted" :
		advanceprinted($_GET["id"]);
	break;
	
	case "getdisplaycols" :
		get_display_cols();
	break;
	
	
	
	case "setdisplaycols" :
		set_display_cols($_GET["cols"]);
	break;
	
	case "getusercols" :
		get_user_cols();
	break;
	
	case "addusercol" :
		add_user_col($_GET["col"]);
	break;
	
	case "deleteusercol" :
		delete_user_col($_GET["id"]);
	break;
	
	case "editusercol" :
		edit_user_col($_GET["id"], $_GET["name"]);
	break;
	
	case "getremindtext":
		echo get_remindtext();
	break;
	
	case "setremindtext":
		set_remindtext($_GET["text"]);
	break;
	
	case "getfine" :
		echo get_fine();
	break;
	
	case "setfine" :
		set_fine($_GET["val"]);
	break;
	
	case "getlpropts" :
		echo get_lpropts();
	break;
	
	case "setlpropts" :
		set_lpropts($_GET["val"]);
	break;
	
	
	case "getformdefaults" :
		echo implode(",", get_formdefaults());
	break;
	
	case "setformdefaults" :
		set_formdefaults($_GET["val"]);
	break;
	
	case "setlatest" :
		set_latest($_GET["date"], $_GET["mode"]);
	break;
	
	case "getlpropts" :
		echo get_lpropts();
	break;
	
	case "setlpropts" :
		set_lpropts($_GET["val"]);
	break;
	
}


?>
