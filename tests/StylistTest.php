<?php

  /**
  * @backupGlobals disabled
  * @backupStaticAttributes disabled
  */

  require_once "src/Stylist.php";
  require_once "src/Client.php";

  $server = 'mysql:host=localhost;dbname=hair_salon_test';
  $user = 'root';
  $pass = 'root';

  $db = new PDO($server, $user, $pass);

  class StylistTest extends PHPUnit_Framework_TestCase {

    protected function tearDown(){
      Stylist::deleteAll();
      Client::deleteAll();
    }

    function testGetId(){
      $name = "xing";
      $stylist = new Stylist($name);
      $stylist->save();
      $result = $stylist->getId();
      $this->assertEquals(true, is_numeric($result));
    }

    function testSave(){
      $stylist = "Xing";
      $test_stylist = new Stylist($stylist);
      $test_stylist->save();

      $client_name = "Zach";
      $client_gender = "male";
      $client_age = 30;
      $client_address = "1201 3rd";
      $client = new Client($client_name, $client_gender, $client_age, $client_address, $test_stylist->getId());
      $executed = $client->save();
      $this->assertTrue($executed, "Task not successfully saved to database");
    }




  }

?>
