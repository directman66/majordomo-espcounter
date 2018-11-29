<?php
/**
* @author espcounter by sannikov dmitriy <sannikovdi@ya.ru>
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 10:01:31 [Jan 03, 2018])
*/
//
//
//ini_set('max_execution_time', '600');
//ini_set ('display_errors', 'off');
class espcounter extends module {
/**
*
* Module class constructor
*
* @access private
*/
function espcounter() {
  $this->name="espcounter";
  $this->title="ESPCounter";
  $this->module_category="<#LANG_SECTION_DEVICES#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;


  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
  $this->checkSettings();
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  $this->data=$out;



  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {


	



$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'espcounterdebug.txt';

$out['MSG_DEBUG']=file_get_contents($file);

//$cmd_rec = SQLSelectOne("SELECT VALUE FROM espcounter_config where parametr='CURRENT'");
//$out['CURRENT']=$cmd_rec['VALUE'];
//$currentid=$cmd_rec['VALUE'];

//$cmd_rec = SQLSelectOne("SELECT * FROM espcounter_devices where FIO='$currentid'");


//$out['ALLDEVICES']=$cmd_rec;		





 if ($this->view_mode=='get') {


$cachedVoiceDir = ROOT . 'cms/cached/';
$file = $cachedVoiceDir . 'espcounterdebug.txt';
$debug = file_get_contents($file);

$debug = "Запускаем цикл по счетчикам <br>\n";
file_put_contents($file, $debug);

$cmd_rec = SQLSelect("SELECT ID FROM espcounter_devices");
foreach ($cmd_rec as $cmd_r)
{
$myid=$cmd_r['ID'];
$debug .= "Начинаем запрашивать счетчик $myid. <br>\n";
file_put_contents($file, $debug);
$this->getcnt($myid);
}

}  

 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }


$this->searchdevices($out, $this->id);

if ($this->view_mode=='get_counters') {
$this->getcnt($this->id);
$this->getrates($this->id);
}  



 if ($this->view_mode=='indata_edit') {
   $this->editdevices($out, $this->id);
 }





 if ($this->view_mode=='indata_del') {
   $this->delete($this->id);
//   $this->redirect("?data_source=$this->data_source&view_mode=node_edit&id=$pid&tab=indata");
   $this->redirect("?");
 }	



  if ($this->view_mode=='getrates') {
//   $this->getrates($this->id);
  }


}

  



function getrates($id) {
/*

if (!$id){



$all_rec = SQLSelect("SELECT * FROM espcounter_devices");
foreach ($all_rec as $rc) {
$this->updaterates($rc['ID']);
}
} else {

$this->updaterates($id);
}
}

function updaterates($id) {

//pmesg($objectname);
$objectname='ESPCounter_'.$id;		
$cmd_rec = SQLSelectOne("SELECT * FROM espcounter_devices where ID='$id'");
$now=time();
//$cmd_rec['MONTH_KM']=round(getHistorySum($objectname.'.rashodt1', $now-2629743 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-2629743 ,$now));
//$cmd_rec['MONTH_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-2629743 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-2629743 ,$now)*SETTINGS_APPMERCURY_T2));

//$cmd_rec['DAY_KM']=round(getHistorySum($objectname.'.rashodt1', $now-86400 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-86400 ,$now));
//$cmd_rec['DAY_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-86400 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-86400 ,$now)*SETTINGS_APPMERCURY_T2));

//$cmd_rec['WEEK_KM']=round(getHistorySum($objectname.'.rashodt1', $now-604800 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-604800 ,$now));
//$cmd_rec['WEEK_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-604800 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-604800 ,$now)*SETTINGS_APPMERCURY_T2));
//
//$cmd_rec['YEAR_KM']=round(getHistorySum($objectname.'.rashodt1', $now-31556926 ,$now))+round(getHistorySum($objectname.'.rashodt2', $now-31556926 ,$now));
//$cmd_rec['YEAR_RUB']=(round(getHistorySum($objectname.'.rashodt1', $now-31556926 ,$now)*SETTINGS_APPMERCURY_T1))+(round(getHistorySum($objectname.'.rashodt2', $now-31556926 ,$now)*SETTINGS_APPMERCURY_T2));
SQLUpdate('espcounter_devices',$cmd_rec);
}

function processSubscription($event_name, $details='') {
  if ($event_name=='HOURLY') {
		$this->getcnt();
		$this->getrates();

  }
*/
 }	
 

function checkSettings() {
  $settings=array(
    array(
    'NAME'=>'APPESPCOUNTER_T', 
    'TITLE'=>'Стоимость Тариф, руб/м3',
    'TYPE'=>'text',
    'DEFAULT'=>'29,81' )
,array('NAME'=>'APPESPCOUNTER_INTERVAL', 
    'TITLE'=>'Период опроса (min)', 
    'TYPE'=>'text',
    'DEFAULT'=>'5'    )
,   array(    'NAME'=>'APPESPCOUNTER_ENABLE', 
    'TITLE'=>'Включен цикл',
    'TYPE'=>'yesno',
    'DEFAULT'=>'1'    )
,   array(    'NAME'=>'APPESPCOUNTER_ENABLEDEBUG', 
    'TITLE'=>'Включена отладка',
    'TYPE'=>'yesno',
    'DEFAULT'=>'2'    )   );

   foreach($settings as $k=>$v) {
    $rec=SQLSelectOne("SELECT ID FROM settings WHERE NAME='".$v['NAME']."'");
    if (!$rec['ID']) {
     $rec['NAME']=$v['NAME'];
     $rec['VALUE']=$v['DEFAULT'];
     $rec['DEFAULTVALUE']=$v['DEFAULT'];
     $rec['TITLE']=$v['TITLE'];
     $rec['TYPE']=$v['TYPE'];
     $rec['DATA']=$v['DATA'];
     $rec['ID']=SQLInsert('settings', $rec);
     Define('SETTINGS_'.$rec['NAME'], $v['DEFAULT']);
    }
   }}


 function indata_edit(&$out, $id) {
  require(DIR_MODULES.$this->name.'/indata_edit.inc.php');
 }
 
 function searchdevices(&$out) {


$mhdevices=SQLSelect("SELECT * FROM espcounter_devices");
$total = count($mhdevices);
for ($i = 0; $i < $total; $i++)
{ 
$ip=$mhdevices[$i]['IPADDR'];
$lastping=$mhdevices[$i]['LASTPING'];
//echo time()-$lastping;
if (time()-$lastping>300) {
$online=ping(processTitle($ip));
    if ($online) 
{SQLexec("update espcounter_devices set ONLINE='1', LASTPING=".time()." where IPADDR='$ip'");} 
else 
{SQLexec("update espcounter_devices set ONLINE='0', LASTPING=".time()." where IPADDR='$ip'");}
}}



  require(DIR_MODULES.$this->name.'/search.inc.php');
 }

// function updatecurrent(&$out) {




  
 
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}



/**

*
* @access public
*/

 function delete($id) {
  $rec=SQLSelectOne("SELECT * FROM espcounter_devices WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM espcounter_devices WHERE ID='".$rec['ID']."'");
 }
/**
* InData edit/add
*
* @access public
*/
 function editdevices(&$out, $id) {	
  require(DIR_MODULES.$this->name.'/indata_edit.inc.php');
 } 


//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
 function getcnt($id) {

$rec=SQLSelectOne("SELECT * FROM espcounter_devices WHERE ID='$id'");

$ip=$rec['IPADDR'];

//set_time_limit(10);
//ob_implicit_flush(true);


//$this->resethci();
////$url="http://192.168.1.208/i2cgo?adr=50";



$url="http://$ip/debug";

$file = file_get_contents($url);
//echo $file;
$ar=explode (" ",strip_tags($file));
$mac=substr($ar[1],0,12);

//echo "<br><br>";

//$url="http://$ip/idesp";

//$file = file_get_contents($url);
//echo $file;
//echo "<br><br>";
$url="http://$ip/sensors";
$file = file_get_contents($url);
//echo $file;

$ar=explode(";",$file);
$c1=explode(":",$ar[1])[1];
$c2=explode(":",$ar[2])[1];
$upd=date('d/m/y H:s');



$rec=SQLSelectOne("SELECT * FROM espcounter_devices WHERE ID='$id'");
$rec['MAC']=$mac;
$rec['TS']=time();
$rec['Total1']=$c1;
$rec['Total2']=$c2;

   
   //UPDATING RECORD
//sg('test.merk',$ok);

    if ($rec['ID']) {
     SQLUpdate('espcounter_devices', $rec); // update
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert('espcounter_devices', $rec); // adding new record
    }





//Запись значений (пока в отсутствие веб-интерфейса) выполняется командами вида
//IP-адрес/i2cgo?adr=50&set=00XXXXXX
//adcX




//создаем устройство

//$classname='ESPCounter';
//$objname=$classname.'_'.$id;

//addClassObject($classname,$objname);


 }
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////


//////////////////////////////////////////////
//////////////////////////////////////////////

/**

*
* @access public
*/
 
/**

*
* @access public
*/
 
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {

  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {

SQLExec('DROP TABLE IF EXISTS espcounter_devices');
SQLExec('DROP TABLE IF EXISTS espcounter_config');
SQLExec('delete from settings where NAME like "%APPESPCOUNTER%"');
SQLExec("delete from pvalues where property_id in (select id FROM properties where object_id in (select id from objects where class_id = (select id from classes where title = 'ESPCounter')))");
SQLExec("delete from properties where object_id in (select id from objects where class_id = (select id from classes where title = 'ESPCounter'))");
SQLExec("delete from objects where class_id = (select id from classes where title = 'ESPCounter')");
SQLExec("delete from classes where title = 'ESPCounter'");	 

  parent::uninstall();

 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data = '') {


$classname='ESPCouner';
addClass($classname); 

$ChangeT1='
$objn=$this->object_title;
$currentcount=$this->getProperty("Total1");
$lasttotal=gg($objn.".lasttotal1");

SQLUpdate("objects", array("ID"=>$this->id, "DESCRIPTION"=>gg($objn.".FIO")." P:".gg($objn.".PvT")." U:".gg($objn.".U")." ".gg("sysdate")."  ".gg("timenow"))); 
if (IsSet($lasttotal) and ($lasttotal<>0) )
{
$rashod=$currentcount-$lasttotal;
sg($objn.".rashodt1",$rashod);}


sg($objn.".lasttimestamp", time());
sg($objn.".lasttotal1", $currentcount);';
$ChangeT2='
$objn=$this->object_title;
$currentcount=$this->getProperty("Total2");
$lasttotal=gg($objn.".lasttotal2");

SQLUpdate("objects", array("ID"=>$this->id, "DESCRIPTION"=>gg($objn.".FIO")." P:".gg($objn.".PvT")." U:".gg($objn.".U")." ".gg("sysdate")."  ".gg("timenow"))); 
if (IsSet($lasttotal) and ($lasttotal<>0) )
{
$rashod=$currentcount-$lasttotal;
sg($objn.".rashodt2",$rashod);}


sg($objn.".lasttimestamp", time());
sg($objn.".lasttotal2", $currentcount);
';

addClassMethod($classname,'ChangeT1',$ChangeT1);	 
addClassMethod($classname,'ChangeT2',$ChangeT2);	 



$prop_id=addClassProperty($classname, 'Adress', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Адрес счетчика'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'ControlLimit', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Лимит'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Cos1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Cos2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Cos3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'CosT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Ia1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока по фазе 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Ia2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока по фазе 2'; //   <-----------
SQLUpdate('properties',$property); }


$prop_id=addClassProperty($classname, 'Ia3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока по фазе 3'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'IaT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Сила тока общая'; //   <-----------
SQLUpdate('properties',$property); }


$prop_id=addClassProperty($classname, 'LimitValue', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Лимит'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Pv1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность по фазе 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Pv2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность по фазе 2'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Pv3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность по фазе 3'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'PvT', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Мощность суммарная'; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Total', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']=''; //   <-----------
SQLUpdate('properties',$property); }



$prop_id=addClassProperty($classname, 'Uv1', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение по фазе 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Uv2', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение по фазе 2'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Uv3', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение по фазе 3'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'U', 7);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Напряжение, среднее значение по 3 фазам'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'rashodt1', 365);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Израсходовано по тарифу 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'rashodt2', 365);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Израсходовано по тарифу 1'; //   <-----------
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Total1', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Текущее значение счетчика по тарифу 1'; //   <-----------
$property['ONCHANGE']="ChangeT1"; //	   	       
SQLUpdate('properties',$property); }

$prop_id=addClassProperty($classname, 'Total2', 0);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Текущее значение счетчика по тарифу 2'; //   <-----------
$property['ONCHANGE']="ChangeT2"; //	   	       
SQLUpdate('properties',$property); }









  $data = <<<EOD
 espcounter_devices: ID int(10) unsigned NOT NULL auto_increment
 espcounter_devices: TITLE varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: IPADDR varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: MAC varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: PORT varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: SN varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: ONLINE varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: STATE varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: TS varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: N1 varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: N2 varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: D1 varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: D2 varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: Total1 varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: Total2 varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: MONTH_KM varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: MONTH_RUB varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: DAY_KM varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: DAY_RUB varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: WEEK_KM varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: WEEK_RUB varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: YEAR_KM varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: YEAR_RUB varchar(100) NOT NULL DEFAULT ''
 espcounter_devices: LASTPING datetime

EOD;
  parent::dbInstall($data);

  $data = <<<EOD
 espcounter_config: parametr varchar(300)
 espcounter_config: value varchar(10000)  
EOD;
   parent::dbInstall($data);



$cmd_rec = SQLSelect("SELECT * FROM espcounter_config");
if ($cmd_rec[0]['EVERY']) {
null;
} else {

$par['parametr'] = 'EVERY';
$par['value'] = 30;		 
SQLInsert('espcounter_config', $par);				
	
$par['parametr'] = 'LASTCYCLE_TS';
$par['value'] = "0";		 
SQLInsert('espcounter_config', $par);						

$par['parametr'] = 'CURRENT';
$par['value'] = "";		 
SQLInsert('espcounter_config', $par);						
		
$par['parametr'] = 'LASTCYCLE_TXT';
$par['value'] = "0";		 
SQLInsert('espcounter_config', $par);						
$par['parametr'] = 'DEBUG';
$par['value'] = "";		 
SQLInsert('espcounter_config', $par);	
}
}


//////////////////////////////////////////////
//////////////////////////////////////////////
	
function average($arr)
{
   if (!is_array($arr)) return false;

   return array_sum($arr)/count($arr);
}





}
// --------------------------------------------------------------------
	
/*
*
* TW9kdWxlIGNyZWF0ZWQgSmFuIDAzLCAyMDE4IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/


//////////////////////////////////////////////
//////////////////////////////////////////////
