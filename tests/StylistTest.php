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

    function testGetClientId(){
      $stylist_name = "xing";
      $stylist = new Stylist($stylist_name);
      $stylist->save();

      $client_name = "Zach";
      $client_gender = "male";
      $client_age = 30;
      $client_address = "1201 3rd";
      $client = new Client($client_name, $client_gender, $client_age, $client_address, $stylist->getId());
      $client->save();
      $result = $client->getId();
      $this->assertTrue(is_numeric($result));
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

    function testDeleteAll(){
      $name = "Xing";
      $test_stylist = new Stylist($name);
      $test_stylist->save();
      $stylist_id = $test_stylist->getId();

      $client_name = "Eliot";
      $client_gender = "male";
      $client_age = 33;
      $client_address = "downtown";
      $test_client = new Client($client_name, $client_gender, $client_age, $client_address, $stylist_id);
      $test_client->save();

      $client_name2 = "Max";
      $client_gender2 = "male";
      $client_age2 = 25;
      $client_address2 = "belletown";
      $test_client2 = new Client($client_name2, $client_gender2, $client_age2, $client_address2, $stylist_id);
      $test_client2->save();

      Client::deleteAll();

      $result = Client::getAll($stylist_id);
      $this->assertEquals([], $result);
    }

    function testFind(){
      $name = "Xing";
      $test_stylist = new Stylist($name);
      $test_stylist->save();
      $stylist_id = $test_stylist->getId();
      $result = Stylist::find($stylist_id);
      $this->assertEquals($test_stylist,$result);
    }

    function testUpdateName(){
      $name = "Xing";
      $test_stylist = new Stylist($name);
      $test_stylist->save();
      $stylist_id = $test_stylist->getId();

      $client_name = "Eliot";
      $client_gender = "male";
      $client_age = 33;
      $client_address = "downtown";
      $test_client = new Client($client_name, $client_gender, $client_age, $client_address, $stylist_id);
      $test_client->save();

      $client_name2 = "Max";
      $client_gender2 = "male";
      $client_age2 = 25;
      $client_address2 = "belletown";

      $test_client->update($client_name2, $client_gender2, $client_age2, $client_address2);

      $this->assertEquals("Max",$test_client->getName());
    }

    function testUpdateGender(){
      $name = "Xing";
      $test_stylist = new Stylist($name);
      $test_stylist->save();
      $stylist_id = $test_stylist->getId();

      $client_name = "Eliot";
      $client_gender = "male";
      $client_age = 33;
      $client_address = "downtown";
      $test_client = new Client($client_name, $client_gender, $client_age, $client_address, $stylist_id);
      $test_client->save();

      $client_name2 = "Max";
      $client_gender2 = "male";
      $client_age2 = 25;
      $client_address2 = "belletown";

      $test_client->update($client_name2, $client_gender2, $client_age2, $client_address2);

      $this->assertEquals("male",$test_client->getGender());
    }

    function testUpdateAge(){
      $name = "Xing";
      $test_stylist = new Stylist($name);
      $test_stylist->save();
      $stylist_id = $test_stylist->getId();

      $client_name = "Eliot";
      $client_gender = "male";
      $client_age = 33;
      $client_address = "downtown";
      $test_client = new Client($client_name, $client_gender, $client_age, $client_address, $stylist_id);
      $test_client->save();

      $client_name2 = "Max";
      $client_gender2 = "male";
      $client_age2 = 25;
      $client_address2 = "belletown";

      $test_client->update($client_name2, $client_gender2, $client_age2, $client_address2);

      $this->assertEquals(25,$test_client->getAge());
    }

    function testUpdateAddress(){
      $name = "Xing";
      $test_stylist = new Stylist($name);
      $test_stylist->save();
      $stylist_id = $test_stylist->getId();

      $client_name = "Eliot";
      $client_gender = "male";
      $client_age = 33;
      $client_address = "downtown";
      $test_client = new Client($client_name, $client_gender, $client_age, $client_address, $stylist_id);
      $test_client->save();

      $client_name2 = "Max";
      $client_gender2 = "male";
      $client_age2 = 25;
      $client_address2 = "belletown";

      $test_client->update($client_name2, $client_gender2, $client_age2, $client_address2);

      $this->assertEquals("belletown",$test_client->getAddress());
    }
  }

?>
