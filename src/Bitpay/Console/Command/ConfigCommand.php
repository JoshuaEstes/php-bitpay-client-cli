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

namespace Bitpay\Console\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ConfigCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('config')
            ->setDescription('Allows you to configure configuration options')
            ->setDefinition(
                array(
                    //new InputOption('editor', 'e', InputOption::VALUE_NONE, 'Open editor'),
                    new InputOption('list', 'l', InputOption::VALUE_NONE, 'List of values'),
                    new InputArgument('setting-key', null, 'Setting Key'),
                    new InputArgument('setting-value', null, 'Setting Value'),
                )
            )
            ->setHelp(
<<<HELP

This command allows you to edit the bitpay configuration file.

To list all the the configuration parameters, funct the command:

    <comment>%command.full_name% --list</comment>


An example of changing one of the configuration parameters is:

    <comment>%command.full_name% KEY VALUE</comment>
    <comment>%command.full_name% network testnet</comment>
    <comment>%command.full_name% network livenet</comment>


HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //if ($input->getOption('editor')) {
        //    $editor = getenv('EDITOR');
        //    if (!$editor) {
        //        throw \Exception('EDITOR variable is not set.');
        //    }
        //    system($editor.' '.$input->getOption('config').' > `tty`');
        //    return 0;
        //}

        if ($input->getOption('list')) {
            $this->listConfiguration($output);
        }
    }

    private function listConfiguration(OutputInterface $output)
    {
        $table      = new Table($output);
        $parameters = $this->getApplication()->getKernel()->getContainer()->getParameterBag()->all();
        ksort($parameters);
        $table->setHeaders(
            array(
                'Config Key',
                'Config Value',
            )
        );

        /**
         * Only display the ones that can be updated or edited
         */
        foreach ($parameters as $key => $value) {
            if ('bitpay' !== substr($key, 0, 6)) {
                continue;
            }

            $table->addRow(array(str_replace('bitpay.', '', $key), $value));
        }

        $table->render();
    }
}
