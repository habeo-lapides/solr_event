<?php

namespace Drupal\solr_event\EventSubscriber;

use Drupal\search_api_solr\Event\PreQueryEvent;
use Drupal\search_api_solr\Event\SearchApiSolrEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

// Use Psr\Log\LoggerInterface;.

/**
 * Pre-Query for SOLR search.
 */
class SolrEventSubscriber implements EventSubscriberInterface {

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

    $query = $event->getSearchApiQuery();
    
    \Drupal::logger('solr_event')->notice($query);

    // $solarium_query = $event->getSolariumQuery();
  }

}
