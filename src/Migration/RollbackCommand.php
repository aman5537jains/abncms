<?php

namespace Aman5537jains\AbnCms\Migration;


use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Console\Migrations\RollbackCommand as BaseRollbackCommand;

class RollbackCommand extends BaseRollbackCommand
{
    use MigrationTrait;

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

    /**
     * {@inheritdoc}
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        array_push($options,
            ['all', null, InputOption::VALUE_NONE, 'Rollback migrations from all available domains.'],
            ['domain', null, InputOption::VALUE_OPTIONAL, 'Rollback migrations from given domains.']
        );

        return $options;
    }
}
