<?php

namespace Drupal\solr_event\EventSubscriber;

use Drupal\search_api_solr\Event\PreQueryEvent;
use Drupal\search_api_solr\Event\SearchApiSolrEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
class SolrEventSubscriber implements EventSubscriberInterface {

  // Protected $logger;
  // Public function __construct(LoggerChannelInterface $logger) {
  //   $this->logger->info(__FILE__ . '::' . __LINE__);
  //   $this->logger = $logger;
  // }.

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
    // $this->logger->info(__FILE__ . '::' . __LINE__);
    \Drupal::logger('my_module')->notice(__FILE__ . '::' . __LINE__);
    
    $query = $event->getSearchApiQuery();
    
    $solarium_query = $event->getSolariumQuery();
    // change tm_X3b_en_title and ss_field_order field names as per you solr field names.
    // Searching the text "testing" in tm_X3b_en_title field and boosting the ss_field_order field
    // having the value as "first".
    $solarium_query->addParam("q",  "(tm_X3b_en_title:testing AND ss_field_order:first^5)");
  }

}
