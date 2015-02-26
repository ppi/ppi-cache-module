<?php
/**
 * This file is part of the PPI Framework.
 *
 * @copyright  Copyright (c) 2011-2013 Paul Dragoonis <paul@ppi.io>
 * @license    http://opensource.org/licenses/mit-license.php MIT
 * @link       http://www.ppi.io
 */

namespace PPI\CacheModule\Command;

use PPI\Console\Command\AbstractCommand;

/**
 * Debug items currently managed by the cache library.
 *
 * @author      Vítor Brandão <vitor@ppi.io>
 */
class CacheDebugCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cache:debug')
            ->setDescription('Debug items currently managed by the cache library')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command helps you debug items currently managed by the cache library.

<info>php %command.full_name%</info>

EOT
            )
        ;
    }
}
