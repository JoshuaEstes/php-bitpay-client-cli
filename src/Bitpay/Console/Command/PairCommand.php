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
use Symfony\Component\Console\Question\Question;
use Bitpay\Bitpay;

/**
 */
class PairCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('pair')
            ->setDescription('Recieve a token for the crytographically secure bitpay api')
            ->setDefinition(
                array(
                    new InputOption('label', null, InputOption::VALUE_OPTIONAL, 'Label', 'php-client-cli'),
                    new InputArgument('pairingcode', InputArgument::REQUIRED, 'Pairing code from your account'),
                )
            )
            ->setHelp(
<<<HELP

<comment>Examples:</comment>

    %command.full_name% 2g1h33

    %command.full_name% --label="POS Terminal 321"

HELP
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $publicKey  = $this->getPublicKeyPath();
        $privateKey = $this->getPrivateKeyPath();
        if (!file_exists($publicKey) || !file_exists($privateKey)) {
            throw new \Exception(
                sprintf(
                    'API keys could not be found in "%s"',
                    $this->getContainer()->getParameter('kernel.root_dir')
                )
            );
        }

        $pairingcode = $input->getArgument('pairingcode');

        if (empty($pairingcode)) {
            $question = new Question(
                '<info>Please enter the Paring Code:</info> ',
                $input->getArgument('pairingcode')
            );
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('Please enter a valid response.');
                }

                return $answer;
            });
            $input->setArgument(
                'pairingcode',
                $this->getHelper('question')->ask(
                    $input,
                    $output,
                    $question
                )
            );
        }

        $label = $input->getOption('label');
        $question = new Question(
            sprintf('<info>Please enter a label [<comment>%s</comment>]:</info> ', $label),
            $label
        );
        $input->setOption(
            'label',
            $this->getHelper('question')->ask(
                $input,
                $output,
                $question
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keyManager = $this->getContainer()->get('key_manager');
        $publicKey = $keyManager->load($this->getPublicKeyPath());
        $sin = new \Bitpay\SinKey();
        $sin->setPublicKey($publicKey);
        $sin->generate();

        $payload = array(
            'id'          => (string) $sin,
            'pairingCode' => $input->getArgument('pairingcode'),
            'label'       => $input->getOption('label'),
        );

        $client = $this->getContainer()->get('client');
        $token  = $client->createToken($payload);
        var_dump($token);
        $output->writeln(
            array(
                sprintf('Token:    %s', $token->getToken()),
                sprintf('Resource: %s', $token->getResource()),
                sprintf('Facade:   %s', $token->getFacade()),
            )
        );
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
