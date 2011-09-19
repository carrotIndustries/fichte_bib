<?php $page=explode(".", basename($_SERVER["PHP_SELF"])); 
$page = $page[0];
?>
<div style="width: 100%; background-color: eeeeee;">
<!--<button OnClick="location.href='.';">Startseite</button>-->
<button OnClick="location.href='listobjects.php';" <?php echo $page=="listobjects"?'style="font-weight:bold;"':""?>>Objekte auflisten</button>
<button OnClick="location.href='addobject.php';" <?php echo $page=="addobject"?'style="font-weight:bold;"':""?>>Objekt hinzuf&uuml;gen</button>
<button OnClick="location.href='listpupils.php';" <?php echo $page=="listpupils"?'style="font-weight:bold;"':""?>>Sch&uuml;ler auflisten</button>
<button OnClick="location.href='addpupil.php';" <?php echo $page=="addpupil"?'style="font-weight:bold;"':""?>>Sch&uuml;ler hinzuf&uuml;gen</button>
<button OnClick="location.href='show_reminds.php';" <?php echo $page=="show_reminds"?'style="font-weight:bold;"':""?>>Mahnungen anzeigen</button>
<button OnClick="location.href = 'quick';">Quickmodus</button>
<button OnClick="if($('adm_expander').style.display == 'none') { $('adm_expander').style.display = 'inline'; this.firstChild.nodeValue = '<'} else { $('adm_expander').style.display = 'none'; this.firstChild.nodeValue = '>';}">&gt;</button>
<span id="adm_expander" style="display: none;">
<button onclick="location.href='location.php'" <?php echo $page=="location"?'style="font-weight:bold;"':""?>>Orte</button>
<button onclick="location.href='listgroups.php'" <?php echo $page=="listgroups"?'style="font-weight:bold;"':""?>>Gruppen</button>
<button onclick="location.href='mediatype.php'" <?php echo $page=="mediatype"?'style="font-weight:bold;"':""?>>Medien</button>
<!--<button onclick="location.href='addgroup.php'">Gruppe hinzuf&uuml;gen</button>-->
<button onclick="location.href='columns.php'" <?php echo $page=="columns"?'style="font-weight:bold;"':""?>>Spalten</button>
<button onclick="location.href='usercols.php'" <?php echo $page=="usercols"?'style="font-weight:bold;"':""?>>Benutzerdef. Spalten</button>
<button onclick="location.href='remindtext.php'" <?php echo $page=="remindtext"?'style="font-weight:bold;"':""?>>Mahnungen</button>
<button onclick="location.href='formdefaults.php'" <?php echo $page=="formdefaults"?'style="font-weight:bold;"':""?>>Formularvoreinstellungen</button>
<button onclick="location.href='latest.php'" <?php echo $page=="latest"?'style="font-weight:bold;"':""?>>Rückgabedatum</button>
<button onclick="location.href='printcodes.php'" <?php echo $page=="printcodes"?'style="font-weight:bold;"':""?>>Barcodes ausdrucken</button>
<button onclick="location.href='updateclasses.php'" <?php echo $page=="updateclasses"?'style="font-weight:bold;"':""?>>Klassen aktualisieren</button>
</span>
</div>
