<?php

namespace Drupal\personal\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 *
 */
class PersonalController extends ControllerBase {

  /**
   *
   */
  public function helloWorld() {
    $output = [];

    $output['#title'] = 'Привітулі';

    $output['#markup'] = 'Привітулі, мене звати Денис!';

    return $output;
  }

}
