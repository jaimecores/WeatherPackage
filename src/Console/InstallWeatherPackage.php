<?php

namespace JaimeCores\WeatherPackage\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallWeatherPackage extends Command
{
    // Command to install the package
    protected $signature = 'weatherpackage:install';

    protected $description = 'Install the WeatherPackage';

    public function handle()
    {
        $this->info('Installing WeatherPackage...');

        $this->info('Publishing configuration...');
        
        if (! $this->configExists('weatherpackage.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }
        
        $this->info('Installed WeatherPackage');
    }
    
    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "JaimeCores\WeatherPackage\WeatherPackageServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }
        
        $this->call('vendor:publish', $params);
    }
}