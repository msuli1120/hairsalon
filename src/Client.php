<?php
  class Client {

    private $id;
    private $name;
    private $gender;
    private $age;
    private $address;
    private $enroll_date;
    private $stylist_id;

    function __construct($name, $gender, $age, $address, $stylist_id, $enroll_date=null, $id=null){
      $this->name = $name;
      $this->gender = $gender;
      $this->age = $age;
      $this->address = $address;
      $this->stylist_id = $stylist_id;
      $this->enroll_date = $enroll_date;
      $this->id = $id;
    }

    function getId(){
      return $this->id;
    }

    function setName($new_name){
      $this->name = (string) $new_name;
    }

    function getName(){
      return $this->name;
    }

    function setGender($new_gender){
      $this->gender = (string) $new_gender;
    }

    function getGender(){
      return $this->gender;
    }

    function setAge($new_age){
      $this->age = (int) $new_age;
    }

    function getAge(){
      return $this->age;
    }

    function setAddress($new_address){
      $this->address = (string) $new_address;
    }

    function getAddress(){
      return $this->address;
    }

    function setStylistId($new_stylist_id){
      $this->stylist_id = (int) $new_stylist_id;
    }

    function getStylistId(){
      return $this->stylist_id;
    }

    function setEnrollDate($new_date){
      $this->enroll_date = (string) $new_date;
    }

    function getEnrollDate(){
      return $this->enroll_date;
    }

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO clients (name, stylist_id, gender, age, address, enroll_date) VALUES ('{$this->getName()}', {$this->getStylistId()}, '{$this->getGender()}', '{$this->getAge()}', '{$this->getAddress()}', NOW())");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function getAll($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM clients WHERE stylist_id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_INT);
      $executed->execute();
      $results = $executed->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function find($id){
      $executed = $GLOBALS['db']->prepare("SELECT * FROM clients WHERE id = :id;");
      $executed->bindParam(':id', $id, PDO::PARAM_INT);
      $executed->execute();
      $result = $executed->fetch(PDO::FETCH_ASSOC);
      if($result['id']==$id){
        $new_client = new Client($result['name'],$result['gender'],(int)$result['age'],$result['address'],$result['stylist_id'],$result['enroll_date'],$result['id']);
        return $new_client;
      }
    }

    function update($new_name, $new_gender, $new_age, $new_address) {
      $executed = $GLOBALS['db']->exec("UPDATE clients SET name = '{$new_name}', gender = '{$new_gender}', age = {$new_age}, address = '{$new_address}' WHERE id = {$this->getId()};");
      if($executed){
        $this->setName($new_name);
        $this->setGender($new_gender);
        $this->setAge($new_age);
        $this->setAddress($new_address);
        return true;
      } else {
        return false;
      }
    }

    static function newest(){
      $executed = $GLOBALS['db']->query("SELECT * FROM clients ORDER BY enroll_date desc LIMIT 3;");
      $results = $executed->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function deleteAll(){
      $GLOBALS['db']->exec("DELETE FROM clients;");
    }

    function delete(){
      $executed = $GLOBALS['db']->exec("DELETE FROM clients WHERE id = {$this->getId()};");
      if (!$executed) {
         return false;
      } else {
         return true;
      }
    }

    static function total(){
      $executed = $GLOBALS['db']->query("SELECT COUNT(name) AS total FROM clients;");
      $result = $executed->fetch();
      return $result['total'];
    }

  }
 ?>
