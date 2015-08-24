<?php namespace SocialEngine\SnifferRules\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SniffCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sniff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect violations of coding standard.';

    /**
     * The exit code of the command
     * @var integer
     */
    protected $exitCode = 0;

    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * The Laravel Config component
     *
     * @var \Illuminate\Config\Repository
     */
    public $config;

    protected $binPath = './vendor/bin';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->app = $this->getLaravel();
        $this->config = $this->app->make('config');

        $options = $this->processOptions();
        $options['extensions'] = 'php';
        $options['colors'] = true;

        $command = $this->buildCommand($this->binPath . '/phpcs', $options);

        $this->info('Running PHP Code Sniffer...');
        $this->info($command);

        passthru($command, $exitCode);

        $this->info('Done.');

        if (!$this->option('no-interaction') && $exitCode !== 0) {
            $answer = $this->ask('Try to automatically fix issues? [Yn]', 'y');

            if (strtolower($answer) == 'n') {
                $this->info('Declined fixes.');

                return $exitCode;
            }

            // Code beautifier takes all the same options (except for colors).
            unset($options['colors']);

            $command = $this->buildCommand($this->binPath . '/phpcbf', $options);

            $this->info('Running PHP Code Beautifier...');
            $this->info($command);

            passthru($command, $exitCode);

            $this->info('Done.');

        }

        return $exitCode;
    }

    /**
     * Returns true if the stream supports colorization. Colorization is
     * disabled if not supported by the stream:
     *  -  Windows without Ansicon and ConEmu
     *  -  non tty consoles
     *
     * @codeCoverageIgnore
     * @return boolean
     */
    protected function terminalHasColorSupport()
    {
        if (DIRECTORY_SEPARATOR == '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI');
        }

        return function_exists('posix_isatty');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['report', null, InputOption::VALUE_OPTIONAL, 'Report type, see phpmd -h for info', null],
            ['report-file', null, InputOption::VALUE_OPTIONAL, 'File to write report to', null],
        ];
    }

    protected function processOptions()
    {
        $standards = $this->config->get('sniffer-rules.standard', ['PSR2']);
        $files = $this->config->get('sniffer-rules.files', ['app']);
        $ignore = $this->config->get('sniffer-rules.ignored', []);

        $seStandardKey = array_search('SocialEngine', $standards);
        if ($seStandardKey !== false) {
            $standards[$seStandardKey] = dirname(dirname(__FILE__)) . '/Standard/SocialEngine';
        }

        $ignoreNamespace = $this->config->get('sniffer-rules.ignoreNamespace', []);
        $allowSnakeCaseMethodName = $this->config->get('sniffer-rules.allowSnakeCaseMethodName', []);

        $options = [
            'standards' => $standards,
            'files' => $files,
            'ignore' => $ignore,
            'runtime-set' => [
                'ignoreNamespace' => $ignoreNamespace,
                'allowSnakeCaseMethodName' => $allowSnakeCaseMethodName
            ]
        ];

        $options = array_merge($options, array_filter($this->option()));

        return $options;
    }

    protected function buildCommand($command, array $options)
    {

        $commandParts = [
            'php',
            $command,
        ];

        // Standards requires special processing
        foreach ($options['standards'] as $standardPath) {
            $commandParts[] = sprintf('--standard="%s"', $standardPath);
        }
        unset($options['standards']);

        // So does files...
        $files = $options['files'];
        unset($options['files']);

        // And runtime-set..
        foreach ($options['runtime-set'] as $configKey => $value) {
            $commandParts[] = sprintf("--runtime-set '%s' '%s'", $configKey, json_encode($value));
        }
        unset($options['runtime-set']);


        foreach($options as $name => $value) {
            if ($value === true) {
                $commandParts[] = '--' . $name;
            } elseif (is_array($value)) {
                $commandParts[] = sprintf('--%s="%s"', $name, implode(',', $value));
            } else {
                $commandParts[] = sprintf('--%s="%s"', $name, $value);
            }
        }

        $commandParts = array_merge($commandParts, $files);
        $command = implode(' ', $commandParts);

        return $command;
    }
}
