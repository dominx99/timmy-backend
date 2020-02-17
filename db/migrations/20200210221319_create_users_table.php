<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("users", ["id" => false, "primary_key" => "id"]);

        $table
            ->addColumn("id", "string")
            ->addColumn("email", "string")
            ->addColumn("password", "string")
            ->addColumn("access_token", "string", ["null" => true])
            ->create();
    }
}
