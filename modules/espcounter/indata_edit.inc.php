<?php
/*
* @version 0.1 (wizard)
*/

  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='espcounter_devices';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");

if (ctype_xdigit($rec['HEXADR'])) {
        $out['HEXADR2']=0;
    } else {
//        $out['HEXADR2']="ВНИМАНИ! Строка  должна состоять только из шестнадцатеричных цифр.";
        $out['HEXADR2']=1;
}

//      $out['HEXADR2']=1;
//sg('test.merk',$this->mode);
  if ($this->mode=='update') {
   $ok=1;
  
 
	 global $title;

   $rec['TITLE']=$title;
//sg('test.merktitle',$title);

  

	 global $port;
   $rec['PORT']=$port;
/*
   if ($rec['PORT']=='') {
    $out['ERR_PORT']=1;
    $ok=0;
   }
*/

	 global $hexadr;
   $rec['HEXADR']=$hexadr;




	 global $ipaddr;
   $rec['IPADDR']=$ipaddr;
/*
   if ($rec['IPADDR']=='') {
    $out['ERR_IPADDR']=1;
    $ok=0;
   }
*/

	 global $model;
   $rec['MODEL']=$model;

	 global $fio;
   $rec['FIO']=$fio;




	 global $phone;
   $rec['PHONE']=$phone;

	 global $street;

   $rec['STREET']=$street;

  global $login;
  $rec['LOGIN']=$login;

  global $password;
  $rec['PASSWORD']=$password;


  global $predsed;
  $rec['PREDSED']=$predsed;


   
   //UPDATING RECORD
//sg('test.merk',$ok);
   if ($ok) {
    if ($rec['ID']) {
     SQLUpdate($table_name, $rec); // update
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
    }
    $out['OK']=1;


   } else {
    $out['ERR']=1;
   }
  }

  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
