<?php

namespace Aman5537jains\AbnCms\Migration;



trait MigrationTrait
{
    /**
     * Set schema.
     *
     * @param string $schema
     */
    protected function connectUsingSchema($schema)
    {
        $default = config("database.default");
        config(['database.connections.'.$default => $schema]);

        $this->getLaravel()['db']->purge();
    }




    protected function runFor($clients)
    {
        $default = config("database.default");
        $defaultSchema = config('database.connections.'.$default);

        foreach ($clients as $client) {
            $config = \Config::get("database.connections.".$default);
            $config["host"] = $client->db_host;
            $config["database"] =  $client->db_database;
            $config["username"] = $client->db_username;
            $config["password"] =$client->db_password;
            $this->connectUsingSchema($config);

            $this->comment("\nSchema: " . $client->domain);

            parent::handle();
        }

        // Reset schema.
        $this->connectUsingSchema($defaultSchema);
    }


}
