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

use Bitpay\DependencyInjection\BitpayExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ApplicationKernel
{
    protected $booted;
    protected $container;
    protected $rootDir;
    protected $input;
    protected $output;

    public function __construct()
    {
        $this->booted = false;
    }

    public function boot()
    {
        if (true === $this->booted) {
            return;
        }

        $this->initialzeContainer();

        $this->booted = true;
    }

    public function getConfiguration()
    {
        return new \Bitpay\Config\Configuration();
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getRootDir()
    {
        if (!$this->rootDir) {
            $this->rootDir = getenv('HOME') . '/.bitpay';
        }

        return $this->rootDir;
    }

    public function getCacheDir()
    {
        return $this->getRootDir() . '/cache';
    }

    public function getLogDir()
    {
        return $this->getRootDir() . '/log';
    }

    protected function initialzeContainer()
    {
        $this->container = $this->buildContainer();
        $this->container->set('kernel', $this);
        $this->container->compile();
    }

    protected function getKernelParameters()
    {
        return array_merge(
            array(
                'kernel.root_dir'  => $this->getRootDir(),
                'kernel.cache_dir' => $this->getCacheDir(),
                'kernel.logs_dir'  => $this->getCacheDir(),
            ),
            $this->getEnvParameters()
        );
    }

    protected function getEnvParameters()
    {
        return array();
    }

    protected function buildContainer()
    {
        $container = $this->getContainerBuilder();
        $container->addObjectResource($this);
        $this->prepareContainer($container);

        $configFile = realpath(getenv('HOME') . '/.bitpay/config.yml');
        $this->getContainerLoader($container)->load($configFile);

        return $container;
    }

    protected function prepareContainer(ContainerInterface $container)
    {
        foreach ($this->getDefaultExtensions() as $ext) {
            $container->registerExtension($ext);
            $container->loadFromExtension($ext->getAlias());
        }
    }

    protected function getContainerBuilder()
    {
        return new ContainerBuilder(new ParameterBag($this->getKernelParameters()));
    }

    protected function getContainerLoader(ContainerInterface $container)
    {
        $locator  = new FileLocator();
        $resolver = new LoaderResolver(
            array(
                new YamlFileLoader($container, $locator),
            )
        );

        return new DelegatingLoader($resolver);
    }

    protected function getDefaultExtensions()
    {
        return array(
            new BitpayExtension(),
        );
    }
}
