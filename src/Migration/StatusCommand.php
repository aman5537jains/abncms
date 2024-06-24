<?php

namespace Aman5537jains\AbnCms\Migration;


use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Console\Migrations\StatusCommand as BaseStatusCommand;

class StatusCommand extends BaseStatusCommand
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
            ['all', null, InputOption::VALUE_NONE, 'Show migrations status from all available domains.'],
            ['domain', null, InputOption::VALUE_OPTIONAL, 'Show migrations status from given domains.']
        );

        return $options;
    }
}
