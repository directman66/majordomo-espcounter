<?php
/*
* @version 0.1 (wizard)
*/

  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }

  $table_name='espcounter_devices';
//  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id' and IPADDR='$ipaddr'");
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");


  if ($this->mode=='update') {

   $ok=1;
  global $ipaddr;
  global $title;
  global $n1;
  global $n2;

  global $d1;
  global $d2;


   $rec['TITLE']=$title;
   $rec['IPADDR']=$ipaddr;

   $rec['D1']=$d1;
   $rec['D2']=$d2;



   
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
