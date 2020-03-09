<?php

use Phinx\Migration\AbstractMigration;

class CreateMeasurementsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("measurements", ["id" => false, "primary_key" => "id"]);

        $table
            ->addColumn("id", "string")
            ->addColumn("plan_id", "string")
            ->addColumn("status", "string", ["default" => "started"])
            ->addColumn("stopped_at", "datetime", ["null" => true, "default" => null])
            ->addColumn("created_at", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->addColumn("updated_at", "timestamp", ["null" => true, "update" => "CURRENT_TIMESTAMP"])
            ->create();
    }
}
