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
            ->addColumn("created_at", "datetime", ["default" => "CURRENT_TIMESTAMP"])
            ->addColumn("updated_at", "datetime", ["null" => true, "update" => "CURRENT_TIMESTAMP"])
            ->create();
    }
}
