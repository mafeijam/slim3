<?php

namespace App\Validation\Rules;

use PDO;
use Respect\Validation\Rules\AbstractRule;

class Unique extends AbstractRule
{
   protected $db;
   protected $table;
   protected $column;
   protected $ignore;
   protected $key;

   public function __construct(PDO $db, $table, $column, $ignore = null, $key = 'id')
   {
      $this->db = $db;
      $this->table = $table;
      $this->column = $column;
      $this->ignore = $ignore;
      $this->key = $key;
   }

   public function validate($input)
   {
      $sql = "select * from $this->table where $this->column = ?";
      $values = [$input];

      if ($this->ignore) {
         $sql .= " and $this->key != ?";
         array_push($values, $this->ignore);
      }

      $query = $this->db->prepare($sql);
      $query->execute($values);
      return $query->rowCount() === 0;
   }
}