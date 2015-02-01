<?php

namespace WebAuthConfHld;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class WebAuthConf extends Application {

  protected function getCommandName(InputInterface $input) {
    return 'web-auth-conf';
  }
  protected function getDefaultCommands() {
    $defaultCommands = parent::getDefaultCommands();
    $defaultCommands[] = new WebAuthConfCommand();
    return $defaultCommands;
  }
  public function getDefinition()
  {
    $inputDefinition = parent::getDefinition();
    // efface le premier argument, qui est le nom de la commande
    $inputDefinition->setArguments();
    return $inputDefinition;
  }
  public function getLongVersion() {
    return parent::getLongVersion() . " -/- web-auth-conf ".__VERSION__;    
  }
}