<?php

namespace Drupal\solr_event\EventSubscriber;

use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\search_api_solr\Event\SearchApiSolrEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
class SolrEventSubscriber implements EventSubscriberInterface {

  protected $logger;

  public function __construct(LoggerChannelInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[SearchApiSolrEvents::PRE_QUERY][] = ['onPreQuery'];
    return $events;
  }

  /**
   * Prepares a Solr search api query.
   *
   * @param \Drupal\search_api_solr\Event\PreQueryEvent $event
   *   The search query event.
   */
  public function onPreQuery(PreQueryEvent $event) {
    $this->logger->info('Custom Solr event occurred (query modification).');

    // Access the query objects.
    // Drupal Search API query.
    $search_api_query = $event->getSearchApiQuery();
    $solarium_query = $event->getSolariumQuery();   // Underlying Solarium query.
  }

}
