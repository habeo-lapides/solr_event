<?php

namespace Drupal\solr_event\EventSubscriber;

use Drupal\Core\File\FileRepository;
use Drupal\Core\File\FileSystemInterface;
use Drupal\search_api_solr\Event\PreQueryEvent;
use Drupal\search_api_solr\Event\SearchApiSolrEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

// Use Psr\Log\LoggerInterface;.

/**
 * Pre-Query for SOLR search.
 */
class SolrEventSubscriber implements EventSubscriberInterface {

  /**
   * Provides helpers to operate on files and stream wrappers.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

/**
 * Constructs an object.
 *
 * @param \Drupal\Core\File\FileSystemInterface $file_system
 *   The file system manager.
//  * @param \Drupal\Core\File\FileRepository $file_repository
//  *   The file repository service.
 */
// public function __construct(FileSystemInterface $file_system, FileRepository $file_repository)
public function __construct(FileSystemInterface $file_system)
{
    $this->fileSystem = $file_system;
    // $this->fileRepository = $file_repository;
}

  /**
   *
   */
  public function writeToFile($query)
  {

      // $path = 'private://solr/';
      // //if ($this->fileSystem->prepareDirectory($path, FileSystemInterface::CREATE_DIRECTORY)) {
      //     $filename = 'solr_log.txt';
      //     $fullPath = $path . $filename;
      //     $fileRepository = \Drupal::service('file.repository');
      //     // $file = $fileRepository->writeData($fullPath, $query);
      //     $file = $fileRepository->writeData($fullPath, $query);

      //     dpm($path);
      //     dpm($fullPath);
      //     dpm($query);
      //     dpm($files);

}

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      SearchApiSolrEvents::PRE_QUERY => 'preQuery',
    ];
  }

  /**
   * Prepares a Solr search api query.
   *
   * @param \Drupal\search_api_solr\Event\PreQueryEvent $event
   *   The search query event.
   */
  public function PreQuery(PreQueryEvent $event) {
    // $query = $event->getSearchApiQuery();
    $solariumQuery = $event->getSolariumQuery();

    // Get the FilterQuery objects.
    $filterQueries = $solariumQuery->getFilterQueries();

    foreach ($filterQueries as $key => $filter_query) {
      if ($key == 'filters_0') {

        $reflectionClass = new \ReflectionClass($filter_query);
        $optionsProperty = $reflectionClass->getProperty('options');
        $optionsProperty->setAccessible(TRUE);
        $options = $optionsProperty->getValue($filter_query);

        $pattern = '/\"(.*?)\"/';

        preg_match($pattern, $options['query'], $matches);
        // dpm($matches[0]);
        $this->writeToFile($matches[0]);

      }

    }

  }

}
