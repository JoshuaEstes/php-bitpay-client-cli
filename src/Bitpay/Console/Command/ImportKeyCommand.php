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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Bitpay\PrivateKey;

/**
 */
class ImportKeyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('import-key')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('public', null, InputOption::VALUE_NONE, ''),
                    new InputOption('private', null, InputOption::VALUE_NONE, ''),
                    new InputArgument('value', InputArgument::REQUIRED, ''),
                )
            )
            ->setHelp(
<<<HELP

HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hexValue = $input->getArgument('value');
        $output->writeln($hexValue);

        $key = \Bitpay\PrivateKey::createFromHex($hexValue);

        var_dump($key);
    }

    protected function getPublicKeyPath()
    {
        return $this->getContainer()->getParameter('bitpay.public_key');
    }

    protected function getPrivateKeyPath()
    {
        return $this->getContainer()->getParameter('bitpay.private_key');
    }
}
