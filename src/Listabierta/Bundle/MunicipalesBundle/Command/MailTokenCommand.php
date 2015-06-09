<?php 
namespace Listabierta\Bundle\MunicipalesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailTokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('census:mailtoken')
             ->setDescription('Fill the token field in census user entities');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$logger = $this->getContainer()->get('logger');
    	
    	$logger->info('Executing command census:mailtoken');

    	$entity_manager = $this->getContainer()->get('doctrine')->getManager();
    	
    	$census_user_repository = $entity_manager->getRepository('Listabierta\Bundle\MunicipalesBundle\Entity\CensusUser');
    	$census_users = $census_user_repository->findAll();
    	
    	if(!empty($census_users))
    	{
    		foreach($census_users as $census_user)
    		{
    			$census_user_token = $census_user->getToken();
    			if(!empty($census_user_token))
    			{
					if($census_user->getId() == 1)
					{
						try
						{
							// Send mail with login link for admin
							$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
							
							$message = \Swift_Message::newInstance()
							->setSubject('Enlace para comenzar la votación')
							->setFrom('info@' . rtrim($host, '.'), 'Info Censo')
							->setTo($census_user->getEmail())
							->setBody(
									$this->renderView(
											'MunicipalesBundle:Mail:token_link.html.twig',
											array(
												'token' => $census_user_token,
											)
									), 'text/html'
							);
							 
							$this->get('mailer')->send($message);
							
	    					$output->writeln('Sent mail with token [' . $census_user_token .  '] for user ID ' . $census_user->getId() . '.');
    					}
    					catch(\Exception $e)
    					{
    						$output->writeln($census_user->getEmail() . ' error: ' . $e->getMessage());
    					}
					}
    			}
    		}
    	}
    	
        $output->writeln('Done.');
    }
}