<?php

namespace Aman5537jains\AbnCms;

use Aman5537jains\AbnCms\Migration\MigrateCommand;
use Aman5537jains\AbnCms\Migration\RollbackCommand;
use Aman5537jains\AbnCms\Migration\StatusCommand;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;

class AbnCmsMigrationServiceProvider extends MigrationServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        parent::register();

        $this->registerMigrateCommand();

        $this->registerMigrateRollbackCommand();

        $this->registerMigrateStatusCommand();

        $this->registerDomain();
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('command.migrate', function ($app) {
            return new MigrateCommand($app['migrator'],$app[Dispatcher::class]);
        });
    }
    protected function registerMigrateRollbackCommand()
    {
        $this->app->singleton('command.migrate.rollback', function ($app) {
            return new RollbackCommand($app['migrator'],$app[Dispatcher::class]);
        });
    }
    protected function registerMigrateStatusCommand()
    {
        $this->app->singleton('command.migrate.status', function ($app) {
            return new StatusCommand($app['migrator'],$app[Dispatcher::class]);
        });
    }
    protected function registerDomain()
    {

        if(!app()->runningInConsole())
        \Aman5537jains\AbnCms\Lib\AbnCms::setDatabase();
    }
}
