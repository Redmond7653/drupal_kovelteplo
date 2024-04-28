<?php

namespace Drupal\personal\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Объявляем наш класс-контроллер.
 */
class PersonalController extends ControllerBase {

  /**
   * {@inheritdoc}
   *
   * В Drupal 8 очень многое строится на renderable arrays и при отдаче
   * из данной функции содержимого для страницы, мы также должны вернуть
   * массив который спокойно пройдет через drupal_render().
   */
  public function helloWorld() {
    $output = [];

    $output['#title'] = 'Привітулі';

    $output['#markup'] = 'Привітулі, мене звати Денис!';

    return $output;
  }

}
