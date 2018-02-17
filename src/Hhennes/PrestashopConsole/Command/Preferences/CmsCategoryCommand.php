<?php
/**
 * 2007-2018 Hennes Hervé
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@h-hennes.fr so we can send you a copy immediately.
 *
 * @author    Hennes Hervé <contact@h-hennes.fr>
 * @copyright 2007-2018 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Preferences;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This commands allow to enable/disable cms categories
 *
 */
class CmsCategoryCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('preferences:cmscategory')
            ->setDescription('Disable or enable a specific cms category')
            ->addArgument(
                'id', InputArgument::REQUIRED, 'cms category id'
            )
            ->addArgument('action', InputArgument::OPTIONAL, 'enable|disable(default)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id_cms = $input->getArgument('id');
        $action = $input->getArgument('action');

        \Context::getContext()->shop->setContext(\Shop::CONTEXT_ALL);

        $cmsCategory = new \CMSCategory($id_cms);

        if ( $cmsCategory->id == NULL ){
            $output->writeln(sprintf("<error>Error Cms category %d doesn't exists</error>",$id_cms));
            return;
        }

        switch ( $action ) {
            case 'enable':
                $cmsCategory->active = 1;
                $output->writeln(sprintf("<info>Enable cms category %d</info>",$id_cms));
                break;
            case 'disable':
            default:
                $output->writeln(sprintf("<info>Disable cms category %d</info>",$id_cms));
                $cmsCategory->active = 0;
                break;
        }

        try {
            $cmsCategory->save();
        } catch (Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }
}
