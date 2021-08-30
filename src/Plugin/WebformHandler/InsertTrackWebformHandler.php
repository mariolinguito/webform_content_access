<?php

namespace Drupal\webform_content_access\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\user\Entity\User;

/**
 *
 * @WebformHandler(
 *   id = "Track submission",
 *   label = @Translation("Track submission"),
 *   category = @Translation("Tracking"),
 *   description = @Translation("Creates a new row into Webform Content Access custom table from Webform Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class InsertTrackWebformHandler extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */

  // Function to be fired after submitting the Webform.
  public function postSave(WebformSubmissionInterface $webform_submission, $update = true) {
    // Get an array of the values from the submission.
    $values = $webform_submission->getData();
    $connection = \Drupal::service('database');
    $id_content = $webform_submission->getSourceEntity()->id();
    $id_user = \Drupal::currentUser()->id();

    $result = $connection->insert('webform_content_access_track')
      ->fields([
        'id_user' => $id_user,
        'id_content' => $id_content,
      ])->execute();
  }
}