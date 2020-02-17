<?php

use Phinx\Migration\AbstractMigration;

class CreateTimeMetersTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("time_meters", ["id" => false, "primary_key" => "id"]);

        $table
            ->addColumn("id", "string")
            ->addColumn("user_id", "string")
            ->addColumn("name", "string")
            ->create();
    }
}
