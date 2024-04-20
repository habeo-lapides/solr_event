<?php

namespace Drupal\solr_event\EventSubscriber;

use Drupal\search_api\Event\IndexFieldsEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
class SolrEventSubscriber implements EventSubscriberInterface {

  protected $logger;

  public function __construct(LoggerInterface $logger) {
    $this->logger->info('getSubscribedEvents');
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {

    $events['search_api.index_fields'][] = ['onIndexFields'];
    return $events;
  }

  /**
   * Manipulate data before indexing in Solr.
   */
  public function onIndexFields(IndexFieldsEvent $event) {

    // $event->getEntity().
    // $event->getFields().
    // dpm($event);
    $this->logger->info('Custom Solr event occurred.');

  }

}
