<?php

namespace Drupal\webform_content_access\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
* Class RestController.
*/
class RestController extends ControllerBase {

  /**
   * Entity query factory.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  /**
   * Constructs a new CustomRestController object.

  * @param \Drupal\Core\Entity\Query\QueryFactory $entityQuery
  * The entity query factory.
  */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   * The formatted JSON response.
   */
  public function getMyContents() {
    $response_array = [];
    $database = \Drupal::database();
    $id_user = \Drupal::currentUser()->id();

    $node_query = $database
      ->select('webform_content_access_track', 'wcat')
      ->condition('wcat.id_user', $id_user, '=')
      ->fields('wcat', ['id_content'])
      ->execute()
      ->fetchAllAssoc('id_content');

    $node_query = array_keys($node_query);

    if ($node_query) {
      $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($node_query);

      foreach ($nodes as $node) {
        $response_array[] = [
          'id' => $node->nid->value,
          'title' => $node->title->value,
        ];
      }
    } else {
      $response_array = ['title' => 'No nodes.'];
    }

    $response = new JsonResponse($response_array);
    return $response;
  }

}