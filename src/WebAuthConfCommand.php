<?php

namespace WebAuthConfHld;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WebAuthConfCommand extends Command {

  protected function configure() 
  {
    $this
      ->setName('web-auth-conf')
      ->setDescription('Configure project user, group and set correct acl.')     
      ->addOption(
		  'path',
		  null,
		  InputOption::VALUE_OPTIONAL,
		  'Project\'s path',
                  './'
		  )      
      ->addOption(
		  'full',
		  null,
		  InputOption::VALUE_NONE,
		  "If set the the entire project will be configured with rules defined with the 'acl-project' key in the config file (user and group 'll be set according to the user and group key')."                  
		  )
      ->addOption(
		  'debug',
		  null,
		  InputOption::VALUE_NONE,
		  "If set the generated command line won't be launch but will be print on the standard output."                  
		  )
      ;
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln("<info>This command needs su privilege in order to use the chown and chmod Linux command.</info>");
    $output->writeln("<comment>If you haven't call this command from the project's root, the full project path must have been given as argument (--path /my/project/).</comment>");
    $config = json_decode(file_get_contents(__CONF_FILE__), true);
    if (empty($config['user']) | empty($config['group'])) {
      $output->writeln("<error>User and group must be set in src/wac-conf.json</error>");
      exit();
    }

    $availableProjectsType = [];
    foreach ($config['project-path'] as $project => $conf) {
      $availableProjectsType[] = $project;
    }
    $dialog = $this->getHelperSet()->get('dialog');
    $projectType = -1;
    while(!in_array( $projectType, $availableProjectsType)) {
      if (-1 != $projectType) {
	$output->writeln("<error>Unrecognized project type, each type must be set in the config file. Available project type are : " . implode(", ", $availableProjectsType) . ".</error>");
	if ($input->getOption('no-interaction')) exit;
      }
      $projectType = $dialog->ask($output, "<question>Project's type to configure ?\t</question>", null,  $availableProjectsType);
    }    
 
    $cmds = $this->buildCmd($input, $config, $projectType);    

    if ($input->getOption('debug')) {
      $output->writeln("<info>The following commands would be launch : ".str_replace('Array', '', print_r($cmds, true))." <info>");
      
    } else {
      array_map('exec', $cmds); 
    }
  }

  private function buildCmd(InputInterface $input, $config, $projectType) 
  {
    $path = $input->getOption('path') . '/';
    $chownPattern = "chown %s:%s %s%s -R";
    $chmodPattern = "chmod %s%s %s%s -R";   

    $cmds = array();
    if ($input->getOption('full')) {
      $cmds[] = sprintf($chownPattern, $config['user'], $config['group'], $path, '');
      $cmds[] = sprintf($chmodPattern, $config['acl-project'], '', $path, '');
    }
    foreach ($config['project-path'][strtolower($projectType)] as $key => $dir) {
      $cmds[] = sprintf($chownPattern, $config['user'], $config['group'], $path, $dir);
      $cmds[] = sprintf($chmodPattern, $config['acl-master'], ','.$config['acl-server'], $path, $dir);
    } 
    return $cmds;
  }

}