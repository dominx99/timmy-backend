<?php

use Phinx\Migration\AbstractMigration;

class CreatePlansTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("plans", ["id" => false, "primary_key" => "id"]);

        $table
            ->addColumn("id", "string")
            ->addColumn("time_meter_id", "string")
            ->addColumn("user_id", "string")
            ->addColumn("start_date", "datetime")
            ->addColumn("end_date", "datetime")
            ->addColumn("min_time", "integer", ["null" => true])
            ->addColumn("max_time", "integer", ["null" => true])
            ->addColumn("created_at", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->addColumn("updated_at", "timestamp", ["null" => true, "update" => "CURRENT_TIMESTAMP"])
            ->create();
    }
}
