<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 BitPay, Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Bitpay\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    /**
     * This is the version of the command line tool used
     */
    const VERSION       = '2.0.0-dev';
    const VERSION_MAJOR = '2';
    const VERSION_MINOR = '0';
    const VERSION_PATCH = '0';
    const VERSION_EXTRA = '-dev';
    const VERSION_BUILD = '';

    protected $kernel;

    public function __construct(ApplicationKernel $kernel)
    {
        $this->kernel = $kernel;

        parent::__construct(
            'BitPay CLI',
            sprintf(
                '%s.%s.%s%s%s',
                self::VERSION_MAJOR,
                self::VERSION_MINOR,
                self::VERSION_PATCH,
                self::VERSION_EXTRA,
                self::VERSION_BUILD
            )
        );

        //$this->getDefinition()->addOptions(
        //    array(
        //        new InputOption('--home', null, InputOption::VALUE_REQUIRED, 'Directory where generated files are'),
        //        new InputOption('--config', '-c', InputOption::VALUE_REQUIRED, 'Configuration file to use'),
        //    )
        //);
    }

    public function getKernel()
    {
        return $this->kernel;
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->kernel->boot();

        return parent::doRun($input, $output);
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new Command\ConfigCommand();
        $commands[] = new Command\ImportKeyCommand();
        $commands[] = new Command\InvoiceCommand();
        $commands[] = new Command\KeygenCommand();
        $commands[] = new Command\PairCommand();
        $commands[] = new Command\UnpairCommand();
        $commands[] = new Command\WhoamiCommand();

        return $commands;
    }
}
