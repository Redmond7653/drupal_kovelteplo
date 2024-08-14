<?php

declare(strict_types=1);

namespace Drupal\ktpersonal\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;

/**
 * Provides a child_nodes block.
 *
 * @Block(
 *   id = "ktpersonal_child_nodes",
 *   admin_label = @Translation("child_nodes"),
 *   category = @Translation("Custom"),
 * )
 */
final class ChildNodesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'example' => $this->t('Hello world!'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form['example'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Example'),
      '#default_value' => $this->configuration['example'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['example'] = $form_state->getValue('example');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {

    $node = \Drupal::routeMatch()->getParameter('node');

    $build = [];

    if ($node instanceof NodeInterface) {
      $nid = $node->id();

//      $parent = $node->get('field_parent_resource')->getValue();

      $nodes = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadByProperties([
          'field_parent_resource' => $nid,
        ]);

      /** @var \Drupal\node\NodeInterface $node */
      foreach ($nodes as $node) {
        $key_array = $node->get('title')->getValue();
        $key = $key_array[0]['value'];
        $node_id_array[$key] = $node->id();
        // $node->getTitle()
        //      $node->toUrl()
      }

      foreach ($node_id_array as $key => $node_id) {
        $options = ['absolute' => TRUE];
        $url = Url::fromRoute('entity.node.canonical', ['node' => $node_id], $options);
        $link = Link::fromTextAndUrl($key, $url);

        $build['content'][] = [
          $link->toRenderable(),
        ];
      }
      $build['#cache'] = [
        'tags' => ["node:$nid"],
        'contexts' => [
          'url',
        ],
      ];

    }
    return $build;
  }

  /**
   *
   */
//  public function getCacheTags() {
//    // With this when your node change your block will rebuild.
//    if ($node = \Drupal::routeMatch()->getParameter('node')) {
//      // If there is node add its cachetag.
//      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $node->id()]);
//    }
//    else {
//      // Return default tags instead.
//      return parent::getCacheTags();
//    }
//  }

}
