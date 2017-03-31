<?php
  class Stylist {
    private $id;
    private $name;

    function __construct($name, $id=null){
      $this->name = $name;
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

    function save(){
      $executed = $GLOBALS['db']->exec("INSERT INTO stylists (name) VALUES ('{$this->getName()}');");
      if($executed){
        $this->id = $GLOBALS['db']->lastInsertId();
        return true;
      } else {
        return false;
      }
    }

    static function deleteAll(){
      $GLOBALS['db']->exec("DELETE FROM stylists;");
    }

    static function getAll(){
      $returned = $GLOBALS['db']->query("SELECT * FROM stylists;");
      $results = $returned->fetchAll(PDO::FETCH_OBJ);
      return $results;
    }

    static function find($id){
      $returned = $GLOBALS['db']->prepare("SELECT * FROM stylists WHERE id = :id;");
      $returned->bindParam(':id', $id, PDO::PARAM_INT);
      $returned->execute();
      $result = $returned->fetch(PDO::FETCH_ASSOC);
      if($result['id']==$id){
        $new_stylist = new Stylist($result['name'], $result['id']);
        return $new_stylist;
      }
    }

  }
?>
