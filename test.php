<!DOCTYPE html>
<!--  This site was created in Webflow. http://www.webflow.com  -->
<!--  Last Published: Fri Mar 20 2020 08:58:43 GMT+0000 (Coordinated Universal Time)  -->
<html data-wf-page="5aba3aebc827a68ccf27a94e" data-wf-site="5aba3aebc827a6495927a94d">
<head>
  <meta charset="utf-8">
  <title>Henri Ducros - Gestion partenaires</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Webflow" name="generator">
  <link href="css/normalize.css" rel="stylesheet" type="text/css">
  <link href="css/webflow.css" rel="stylesheet" type="text/css">
  <link href="css/henri-ducros-gestion-partenaires.webflow.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
  <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->

  <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
  <script type="text/Javascript" >
<!--
function getXhr(){
	var xhr = null; 
	if(window.XMLHttpRequest) // Firefox et autres
	   xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // Internet Explorer 
	   try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	else { // XMLHttpRequest non supporté par le navigateur 
	   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
	   xhr = false; 
	} 
	return xhr;
}
 
/**
* Méthode qui sera appelée sur le click du bouton
*/
function go(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			leselect = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('reponseOk').innerHTML = leselect;
		}
	}

	
	
	var id_adr1 = document.getElementById('ag_adr1').value;
	var id_adr2 = document.getElementById('ag_adr2').value;
	var id_cp = document.getElementById('ag_cp').value;
	var id_ville = document.getElementById('ag_ville').value;
	var id_retrait = document.getElementById('ag_retrait').value;
	var id_livraison = document.getElementById('ag_livraison').value;
	
	xhr.open("POST","valid.php",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	cletraplus =document.getElementById('cletraplus').value;
	daterdv =document.getElementById('daterdv').value;
	//alert(cletraplus + ' - ' + daterdv);
	//xhr.send("idCle="+cletraplus+"&idRdv="+daterdv);
	xhr.send("idCle="+cletraplus+"&idRdv="+daterdv+"&idAdr1="+id_adr1+"&idAdr2="+id_adr2+"&idCP="+id_cp+"&idVille="+id_ville+"&idRetrait="+id_retrait+"&idLivraison="+id_livraison);
	//xhr.send("idRdv="+daterdv);
	
	//sel = document.getElementById('auteur');
	//idauteur = sel.options[sel.selectedIndex].value;
	//xhr.send("idAuteur="+idauteur);
}
//-->
</script>  
  <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link href="images/webclip.png" rel="apple-touch-icon">
  
  <style>
    {
        box-sizing: border-box;
    }
    /* Set additional styling options for the columns*/
    .column {
    float: left;
    width: 50%;
    }

    .row:after {
    content: "";
    display: table;
    clear: both;
    }
	</style>
</head>
<body>
<?php
session_start();
  
//include "../Site/pages/logbdd.php";
$host = "prm.hducros.fr"; // Machine hébergeant la base 
$bdd = "db_prmhduc33"; // Nom de la base de données
$user = "prmhduc33db"; // user
$password = "1Mafx86s_Rbjyccu"; // password

$corresp = '05007';

$link = new mysqli($host, $user, $password, $bdd);

/* Vérification de la connexion */
if ($link->connect_errno) {
    printf("Échec de la connexion : %s\n", $link->connect_error);
    exit();
}


// RECUPERATION DES POSITIONS
$sql1="SELECT * from prm_liv where recorr='$corresp'";
if ($res1 = $link->query($sql1))
{
	$lst_hist = array();	// toutes les positions

	$lst_quai = array();	// positions à quai 				(redliv ==0 && redsol == 0 && redsai != 0 && redrem == 0)<-.
	$lst_arri = array();	// positions en arrivages 			(redliv ==0 && redsol == 0 && redsai == 0 && redrem == 0)  |-- à voir si quai et mtou sont réellement identiques (rdv)
	$lst_mtou = array();	// positions à mettre en tournée 	(redliv ==0 && redsol == 0 && redsai != 0 && redrem == 0)<-'
	$lst_rtou = array();	// positions en retour de tournée	(redliv ==0 && redsol == 0 && redsai != 0 && redrem == $today)

	$nb_pos=0;
	while($row1 = $res1->fetch_assoc())
	{
		$csoc = $row1["csoc"];
		$cagc = $row1["cagc"];
		$reccli = $row1["reccli"];
		$rerece = $row1["rerece"];
		
		$lst_hist["$nb_pos"]["cletraplus"] = "$csoc$cagc$reccli$rerece";
		$lst_hist["$nb_pos"]["rerece"] = str_replace ( " ", "&#160;", sprintf("%9s", $row1["rerece"]));
		$lst_hist["$nb_pos"]["rerefe"] = $row1["rerefe"];
		$lst_hist["$nb_pos"]["refori"] = $row1["refori"];
		$lst_hist["$nb_pos"]["redexp"] = $row1["redexp"];
		$lst_hist["$nb_pos"]["redsai"] = $row1["redsai"];
		$lst_hist["$nb_pos"]["redest"] = $row1["redest"];
		$lst_hist["$nb_pos"]["readr1"] = $row1["readr1"];
		$lst_hist["$nb_pos"]["readr2"] = $row1["readr2"];
		$lst_hist["$nb_pos"]["recpod"] = $row1["recpod"];
		$lst_hist["$nb_pos"]["reloca"] = str_replace ( " ", "&#160;", substr($row1["reloca"],0,20));
		$lst_hist["$nb_pos"]["recpay"] = $row1["recpay"];
		$lst_hist["$nb_pos"]["renbco"] = $row1["renbco"];
		$lst_hist["$nb_pos"]["renbum"] = $row1["renbum"];
		$lst_hist["$nb_pos"]["renbem"] = $row1["renbem"];
		$lst_hist["$nb_pos"]["repdsr"] = $row1["repdsr"];
		$lst_hist["$nb_pos"]["remcr"] = $row1["remcr"];
		$lst_hist["$nb_pos"]["remass"] = $row1["remass"];
		$lst_hist["$nb_pos"]["reins1"] = $row1["reins1"];
		$lst_hist["$nb_pos"]["reins2"] = $row1["reins2"];
		$lst_hist["$nb_pos"]["recontact1"] = $row1["recontact1"];
		$lst_hist["$nb_pos"]["retelc1"] = $row1["retelc1"];
		$lst_hist["$nb_pos"]["remail1"] = $row1["remail1"];
		$lst_hist["$nb_pos"]["redlis"] = $row1["redlis"];
		$lst_hist["$nb_pos"]["redliv"] = $row1["redliv"];
		$lst_hist["$nb_pos"]["redsol"] = $row1["redsol"];
		$lst_hist["$nb_pos"]["redrem"] = $row1["redrem"];
		$lst_hist["$nb_pos"]["redrdv"] = $row1["redrdv"];

		if ( $lst_hist["$nb_pos"]["redliv"] == 0 && $lst_hist["$nb_pos"]["redsol"] == 0 && $lst_hist["$nb_pos"]["redsai"] != 0 && $lst_hist["$nb_pos"]["redrem"] == 0 )
			$lst_quai[] = &$lst_hist["$nb_pos"];
		if ( $lst_hist["$nb_pos"]["redliv"] == 0 && $lst_hist["$nb_pos"]["redsol"] == 0 && $lst_hist["$nb_pos"]["redsai"] == 0 && $lst_hist["$nb_pos"]["redrem"] == 0 )
			$lst_arri[] = &$lst_hist["$nb_pos"];
		if ( $lst_hist["$nb_pos"]["redliv"] == 0 && $lst_hist["$nb_pos"]["redsol"] == 0 && $lst_hist["$nb_pos"]["redsai"] != 0 && $lst_hist["$nb_pos"]["redrem"] == 0 )
			$lst_mtou[] = &$lst_hist["$nb_pos"];
		if ( $lst_hist["$nb_pos"]["redliv"] == 0 && $lst_hist["$nb_pos"]["redsol"] == 0 && $lst_hist["$nb_pos"]["redsai"] != 0 && $lst_hist["$nb_pos"]["redrem"] == "200401" )
			$lst_rtou[] = &$lst_hist["$nb_pos"];
		
		$nb_pos++;
	}
	
	//print_r($lst_hist);
	//print_r($lst_quai);
	
	$js_lst_hist = json_encode($lst_hist);
	$js_lst_quai = json_encode($lst_quai);
	$js_lst_arri = json_encode($lst_arri);
	$js_lst_mtou = json_encode($lst_mtou);
	$js_lst_rtou = json_encode($lst_rtou);
	//print_r($lst_rtou);
}

// RECUPERATION DES SITUATIONS
$sql2="select * from prm_situ";
$lst_situ = array();
if ($res2 = $link->query($sql2))
{
	while($row2 = $res2->fetch_assoc())
	{
		$module = $row2["module"];
		$situ = $row2["situ"];
		$lst_situ[$module][$situ] = $row2["libelle"];
	}
	$js_lst_situ = json_encode($lst_situ);
}

// RECUPERATION DES JUSTIFICATIONS
$sql3="select * from prm_just";
//$lst_just = array();
if ($res3 = $link->query($sql3))
{
	while($row3 = $res3->fetch_assoc())
	{
		$situ = $row3["situ"];
		$just = $row3["just"];
		$lst_just[$situ][$just] = $row3["libelle"];
	}
	$js_lst_just = json_encode($lst_just);
}
?>
 

  <div>
    <div data-duration-in="300" data-duration-out="100" class="tabs w-tabs">
	
	  <!-- Définition des onglets -->
      <div class="w-tab-menu">
        <a data-w-tab="Tab 1" class="w-inline-block w-tab-link w--current">
          <div>Quai</div>
        </a>
        <a data-w-tab="Tab 2" class="w-inline-block w-tab-link">
          <div>Arrivage</div>
        </a>
        <a data-w-tab="Tab 3" class="tab-link-tab-3 w-inline-block w-tab-link">
          <div>Mise en Tournée</div>
        </a>
        <a data-w-tab="Tab 4" class="w-inline-block w-tab-link">
          <div>Retour de tournée</div>
        </a>
        <a data-w-tab="Tab 5" class="tab-link-tab-5 w-inline-block w-tab-link">
          <div>Historique</div>
        </a>
        <a data-w-tab="Tab 6" class="tab-link-tab-6 w-inline-block w-tab-link">
          <div>Palettes Europe</div>
        </a>
      </div>
	  
	  <!-- Contenu des onglets -->
      <div class="w-tab-content">
		
		<!-- QUAI -->
        <div data-w-tab="Tab 1" class="w-tab-pane">
          <br>
		  <div class="w-container">
  			<table
			  id="tb_quai"
			  data-toolbar="#toolbar"
			  data-toggle="table"
			  data-search="true"
			  data-show-refresh="true"
			  data-show-toggle="true"
			  data-show-fullscreen="true"
			  data-show-columns="true"
			  data-show-columns-toggle-all="true"
			  data-detail-view="true"
			  data-show-export="true"
			  data-click-to-select="true"
			  data-detail-formatter="detailFormatter"
			  data-minimum-count-columns="2"
			  data-show-pagination-switch="true"
			  data-pagination="true"
			  data-id-field="id"
			  data-page-list="[10, 25, 50, 100, all]"
			  data-response-handler="responseHandler">
			</table>
		  </div>


		</div>
		
		<!-- Arrivage -->
        <div data-w-tab="Tab 2" class="w-tab-pane">
          <br>
		  <div class="w-container">
  			<table
			  id="tb_arri"
			  data-toolbar="#toolbar"
			  data-toggle="table"
			  data-search="true"
			  data-show-refresh="true"
			  data-show-toggle="true"
			  data-show-fullscreen="true"
			  data-show-columns="true"
			  data-show-columns-toggle-all="true"
			  data-detail-view="true"
			  data-show-export="true"
			  data-click-to-select="true"
			  data-detail-formatter="detailFormatter"
			  data-minimum-count-columns="2"
			  data-show-pagination-switch="true"
			  data-pagination="true"
			  data-id-field="id"
			  data-page-list="[10, 25, 50, 100, all]"
			  data-response-handler="responseHandler">
			</table>
		  </div>
        </div>
		
		<!-- Mise en Tournée -->
		
        <div data-w-tab="Tab 3" class="tab-pane-tab-3 w-tab-pane w--tab-active">
          <br>
		  <div class="w-container">
  			<table
			  id="tb_mtou"
			  data-toolbar="#toolbar"
			  data-toggle="table"
			  data-search="true"
			  data-show-refresh="true"
			  data-show-toggle="true"
			  data-show-fullscreen="true"
			  data-show-columns="true"
			  data-show-columns-toggle-all="true"
			  data-detail-view="true"
			  data-show-export="true"
			  data-click-to-select="true"
			  data-detail-formatter="detailFormatter"
			  data-minimum-count-columns="2"
			  data-show-pagination-switch="true"
			  data-pagination="true"
			  data-id-field="id"
			  data-page-list="[10, 25, 50, 100, all]"
			  data-response-handler="responseHandler">
			</table>
		  </div>
        </div>
		
		<!-- Retour de tournée -->
        <div data-w-tab="Tab 4" class="w-tab-pane">
          <!-- <div>Retour de tournée</div> -->
		  <br>
          <div class="w-container">
            <div class="w-form">
              <form id="email-form" name="email-form" data-name="Email Form">
				<label for="name">Livraison</label>
				<input type="text" class="w-input" maxlength="256" name="name" data-name="Name" id="livr_retour" oninput="modif_livr_retour();">
				<select id="select_retour" name="field" class="w-select" onchange="if (this.selectedIndex) modif_select_retour();">
					<option value="">Select one...</option>
<?php
	foreach ($lst_rtou as $key => $liv)
	{
		echo "<option value='$key'><pre>".$liv['rerece']." - ".$liv['recpod']." ".$liv['reloca']." ".$liv['recpay']." - ".$liv['redest']." ".$liv['readr1']." ".$liv['readr2']."</pre></option>";
	}
?>
				</select>
				<br>
                <div class="w-layout-grid grid">
					<label for="name-2" class="field-label">Destinataire</label>
					<label id="retour_dest" for="name-2" class="field-value"></label>
					<label for="name-2" class="field-label">Adresse 1</label>
					<label id="retour_adr1" for="name-2" class="field-value"></label>
					<label for="name-2" class="field-label">Adresse 2</label>
					<label id="retour_adr2" for="name-2"  class="field-value"></label>
					<label for="name-2" class="field-label">CP Localité</label>
					<label id="retour_cplocacp" for="name-2" class="field-value"></label>
				</div>
				<br>
                <div class="w-row">
                  <div class="w-col w-col-3"><label for="name-2" class="field-label-2">Situation</label></div>
                  <div class="w-col w-col-3">
					<select id="select_situ" name="field-2" class="w-select" onchange="if (this.selectedIndex) modif_select_situ();">
						<option value="">Select one...</option>
<?php
	foreach ($lst_situ[4] as $key => $lib)
	{
		echo "<option value='$key'>".$key." - ".$lib."</option>";
	}
?>
					</select>
				  </div>
                  <div class="w-col w-col-3"><label for="name-2" class="field-label-3">Justification</label></div>
                  <div class="w-col w-col-3">
					<select id="select_just" name="field-3" class="w-select">
						<option value="">Select one...</option>
					</select>
				  </div>
                </div><input type="submit" value="Valider" data-wait="Please wait..." class="w-button"></form>
              <div class="w-form-done">
                <div>Thank you! Your submission has been received!</div>
              </div>
              <div class="w-form-fail">
                <div>Oops! Something went wrong while submitting the form.</div>
              </div>
            </div>
          </div>
        </div>
<script type="text/Javascript" >
<!--
function modif_select_retour(){
	var tab = <?php echo $js_lst_rtou?>;
	var sel = document.getElementById('select_retour');
	var key = sel.options[sel.selectedIndex].value;

	document.getElementById('retour_dest').innerHTML=tab[key]['redest'];
	document.getElementById('retour_adr1').innerHTML=tab[key]['readr1'];
	document.getElementById('retour_adr2').innerHTML=tab[key]['readr2'];
	document.getElementById('retour_cplocacp').innerHTML=tab[key]['recpod'] + " " + tab[key]['reloca'] + " " + tab[key]['recpay'];
	sel.selectedIndex=0
}

function modif_livr_retour(){
	var livr = document.getElementById('livr_retour').value;

	var tab = <?php echo $js_lst_rtou?>;
	var sel = document.getElementById('select_retour');
	var opt;
	for ( var i = 0, len = sel.options.length; i < len; i++ ) {
		if ( sel.options[i].innerHTML.indexOf(livr)>0 ){
			sel.options[sel.selectedIndex].selected=false;
			sel.selectedIndex=i;
			sel.options[sel.selectedIndex].selected=true;
			var key = sel.options[i].value;
			document.getElementById('retour_dest').innerHTML=tab[key]['redest'];
			document.getElementById('retour_adr1').innerHTML=tab[key]['readr1'];
			document.getElementById('retour_adr2').innerHTML=tab[key]['readr2'];
			document.getElementById('retour_cplocacp').innerHTML=tab[key]['recpod'] + " " + tab[key]['reloca'] + " " + tab[key]['recpay'];				
			return;
		}
	}

	sel.options[sel.selectedIndex].selected=false;
	sel.selectedIndex=0;
	sel.options[sel.selectedIndex].selected=true;
	document.getElementById('retour_dest').innerHTML="";
	document.getElementById('retour_adr1').innerHTML="";
	document.getElementById('retour_adr2').innerHTML="";
	document.getElementById('retour_cplocacp').innerHTML="";	
}

function modif_select_situ(){
	var sel_situ = document.getElementById('select_situ');
	var sel_just = document.getElementById('select_just');
	var dest = document.getElementById('retour_dest').innerHTML;
	var tab = <?php echo $js_lst_just?>;
	var situ = sel_situ.options[sel_situ.selectedIndex].value;

	sel_just.innerHTML = "";
	var option = document.createElement("option");
	option.text = "Select one...";
	option.value= "";
	sel_just.add(option);
	
	
	if(dest.length){
		for (var just in tab[situ]){
			//alert(just + ' - ' + tab[situ][just]);
			var option = document.createElement("option");
			option.text = just + ' - ' + tab[situ][just];
			option.value= just;
			sel_just.add(option); 
		}
	}
	else{
		sel_situ.options[sel_situ.selectedIndex].selected=false;
		sel_situ.selectedIndex=0;
		sel_situ.options[sel_situ.selectedIndex].selected=true;		
	}
	
	
}
//-->
</script> 		
		<!-- Historique -->
        <div data-w-tab="Tab 5" class="w-tab-pane">
          <div>Historique</div>
        </div>
		
		<!-- Palettes Europe -->
        <div data-w-tab="Tab 6" class="w-tab-pane">
          <div>Palettes Europe</div>
        </div>
		
      </div>
    </div>
  </div>
  
  <!-- Bootstrap -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
  
<script type="text/Javascript" >
<!--
	function detailFormatter(index, row) {
		var html = []
		html.push("<div class='row'><div class='column left'>")
		$.each(row, function (key, value) {
			
			html.push("<p><b>" + key + ":</b> " + value + "</p>")
		})
		html.push("</div><div class='column right'><p><b>Historique des évènements</b></div></div>")
		return html.join('')
	}
	function operateFormatter(value, row, index) {
		return [
		  '<a class="edit" href="javascript:void(0)" title="Edit">',
		  '<i class="fa fa-edit"></i>',
		  '</a>  ',

		].join('')
	}
	function responseHandler(res) {
		$.each(res.rows, function (i, row) {
			row.state = $.inArray(row.id, selections) !== -1
		})
		return res
	}
	window.operateEvents = {
		'click .edit': function (e, value, row, index) {
			alert('You click like action, row: ' + JSON.stringify(row))
		},

	}
	
	$('#tb_quai').bootstrapTable({
		locale: $('#fr-FR').val(),
		columns: [
			[{
				title: '#',
				field: 'cletraplus'
			}, {
				title: 'Récépissé',
				field: 'rerece'
			}, {
				title: 'Dept',
				field: 'recpod'
			}, {
				title: 'Localité',
				field: 'reloca'
			}]				
		],
		data: <?php echo $js_lst_quai?>
	});
	
	$('#tb_arri').bootstrapTable({
		locale: $('#fr-FR').val(),
		columns: [
			[{
				field: 'state',
				checkbox: true,
			}, {
				title: '#',
				field: 'cletraplus'
			}, {
				title: 'Récépissé',
				field: 'rerece'
			}, {
				title: 'Dept',
				field: 'recpod'
			}, {
				title: 'Localité',
				field: 'reloca'
			}]				
		],
		data: <?php echo $js_lst_arri?>
	});
	
		$('#tb_mtou').bootstrapTable({
		locale: $('#fr-FR').val(),
		columns: [
			[{
				field: 'state',
				checkbox: true,
			}, {
				title: '#',
				field: 'cletraplus'
			}, {
				title: 'Récépissé',
				field: 'rerece'
			}, {
				title: 'Dept',
				field: 'recpod'
			}, {
				title: 'Localité',
				field: 'reloca'
			}]				
		],
		data: <?php echo $js_lst_mtou?>
	});
//}
// -->
</script>

  <!-- Webflow -->
  <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.4.1.min.220afd743d.js?site=5aba3aebc827a6495927a94d" type="text/javascript" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="js/webflow.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
</body>
</html>