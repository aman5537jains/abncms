<?php
namespace Aman5537jains\AbnCms\Migration;

// use App\Library\Venture;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Console\Migrations\MigrateCommand as BaseMigrateCommand;

class MigrateCommand extends BaseMigrateCommand
{
    use MigrationTrait;

    public function __construct(Migrator $migrator,Dispatcher $dispatcher)
    {
        $this->signature .= "
                {--all : Run migrations in all available schemas.}
                {--domain= : Run migrations in given domain.}
        ";

        parent::__construct($migrator,$dispatcher);
    }

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->runFor(\Aman5537jains\AbnCms\Lib\AbnCms::getAllClients());
        }
        else if ($domains = $this->option('domain')) {
            $this->runFor(\Aman5537jains\AbnCms\Lib\AbnCms::getAllClients($domains));
        } else {
            $this->runFor(\Aman5537jains\AbnCms\Lib\AbnCms::getAllClients());
            // parent::handle();
        }
    }
}
