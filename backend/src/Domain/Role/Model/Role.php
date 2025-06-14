<?php 
namespace App\Domain\Role\Model;

use JsonSerializable;

class Role implements JsonSerializable {
  private $id;
  private $roleName;


  public function __construct($id, $roleName = null) {
    $this->id = $id;
    $this->roleName = $roleName;
  }

  /**
   * Get the value of id
   */ 
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */ 
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of roleName
   */ 
  public function getRoleName()
  {
    return $this->roleName;
  }

  /**
   * Set the value of roleName
   *
   * @return  self
   */ 
  public function setRoleName($roleName)
  {
    $this->roleName = $roleName;

    return $this;
  }

  public function jsonSerialize(): array
  {
      return [
          'id' => $this->id,
          'role_name' => $this->roleName,  
      ];
  }

}