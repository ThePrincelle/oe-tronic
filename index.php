<?php
$modbus_ip='192.168.9.18';
$modbus_port=20108;

require_once("Diematic.class.php");

//get values for parameters
if (isset($_POST['submit'])) $action=$_POST['submit']; else $action=null;
if (isset($_POST['mode_chauffage'])) $mode_chauffage=intval($_POST['mode_chauffage']); else $mode_chauffage=null;
if (isset($_POST['mode_ecs'])) $mode_ecs=intval($_POST['mode_ecs']);else $mode_ecs=null;
if (isset($_POST['nb_jour_antigel'])) $nb_jour_antigel=intval($_POST['nb_jour_antigel']); else $nb_jour_antigel=null;

if (isset($_POST['cons_jour_a'])) $cons_jour_a=round($_POST['cons_jour_a'],1); else $cons_jour_a=null;
if (isset($_POST['cons_nuit_a'])) $cons_nuit_a=round($_POST['cons_nuit_a'],1); else $cons_nuit_a=null;
if (isset($_POST['cons_antigel_a'])) $cons_antigel_a=round($_POST['cons_antigel_a'],1); else $cons_antigel_a=null;

if (isset($_POST['cons_ecs'])) $cons_ecs=round($_POST['cons_ecs'],1); else $cons_ecs=null;
if (isset($_POST['cons_ecs_nuit'])) $cons_ecs_nuit=round($_POST['cons_ecs_nuit'],1); else $cons_ecs_nuit=null;

if (isset($_GET['log'])) $log=intval($_GET['log']); else $log=null;
if (isset($_GET['view'])) $view=$_GET['view']; else $view=null;


// function used to generate html content
function get_include_contents($filename,$data=NULL) {

	ob_start();
	require ($filename);
	$contents = ob_get_contents();
	
	ob_end_clean();
	return $contents;
}

//Creation of regulator access
$regulator=new Diematic($modbus_ip,$modbus_port);

//update mode if necessary
if ( ($action=='OK') && ($mode_chauffage !=0)) {
	$regulator->setMode($mode_chauffage,$nb_jour_antigel,$mode_ecs);
} 
//set time if necessary
else if ($action=='Synchro Heure') {
	$regulator->setTime();
}
else if ($action=='Valider Temp') {
	$regulator->setTemp($cons_jour_a,$cons_nuit_a,$cons_antigel_a);
	$regulator->setEcsTemp($cons_ecs,$cons_ecs_nuit);
}

//request data synchro
$regulator->synchro();

if ($view=="page2") echo get_include_contents("page2.ihm.php",$regulator->diematicReg);
else echo get_include_contents("page.ihm.php",$regulator->diematicReg);

if ($log==1) echo "<PRE>",$regulator->log,"</PRE>";		
		
unset($regulator);

//
?>