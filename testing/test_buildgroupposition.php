<?php
// kwtest library
require_once('kwtest/kw_web_tester.php');
require_once('kwtest/kw_db.php');

$path = dirname(__FILE__)."/..";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once('models/buildgroupposition.php');

class BuildGroupPositionTestCase extends KWWebTestCase
{
  var $url           = null;
  var $db            = null;
  var $projecttestid = null;
  var $logfilename   = null;
  
  function __construct()
    {
    parent::__construct();
    require('config.test.php');
    $this->url = $configure['urlwebsite'];
    $this->db  =& new database($db['type']);
    $this->db->setDb($db['name']);
    $this->db->setHost($db['host']);
    $this->db->setUser($db['login']);
    $this->db->setPassword($db['pwd']);
    $this->logfilename = $cdashpath."/backup/cdash.log";
    }
   
  function testBuildGroupPosition()
    {

    $db = pdo_connect($this->db->dbo->host, $this->db->dbo->user, $this->db->dbo->password);
    pdo_select_db("cdash4simpletest", $db);

    $buildgroupposition = new BuildGroupPosition();

    $buildgroupposition->GroupId = 0;
    if($buildgroupposition->Exists())
      {
      $this->fail("Exists() should return false when GroupId is 0");
      return 1;
      }

    $buildgroupposition->GroupId = 1;
    $buildgroupposition->SetValue("POSITION", 1);
    $buildgroupposition->SetValue("STARTTIME", date("Y-m-d H:i:s", time() - 1));
    $buildgroupposition->SetValue("ENDTIME", date("Y-m-d H:i:s"));

    //call save twice to cover different execution paths
    if(!$buildgroupposition->Add())
      {
      $this->fail("Add() returned false when it should be true.\n");
      return 1;
      }
    if($buildgroupposition->Add())
      {
      $this->fail("Add returned true when it should be false.\n");
      return 1;
      }
    $this->pass("Passed");
    return 0;
    }
}
?>
