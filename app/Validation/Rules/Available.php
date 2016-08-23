<?php

namespace App\Validation\Rules;

use PDO;
use Respect\Validation\Rules\AbstractRule;

class Available extends AbstractRule
{
   protected $db;
   protected $table;
   protected $column;

   public function __construct(PDO $db, $table, $column)
   {
      $this->db = $db;
      $this->table = $table;
      $this->column = $column;
   }

   public function validate($input)
   {
      $sql = "select * from $this->table where $this->column = ?";
      $query = $this->db->prepare($sql);
      $query->execute([$input]);
      return $query->rowCount();
   }
}