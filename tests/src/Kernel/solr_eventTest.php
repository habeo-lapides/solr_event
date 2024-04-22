<?php

namespace Drupal\Tests\solr_event\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\solr_event\EventSubscriber\SolrEventSubscriber;

/**
 * Test to ensure 'solr_event_subscriber' service is reachable.
 *
 * @group solr_event
 * @group examples
 *
 * @ingroup solr_event
 */
class SolrEventSubscriberTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['solr_event'];

  /**
   * Test for existence of 'solr_event_subscriber' service.
   */
  public function testSolrEventSubscriber() {
    $subscriber = $this->container->get('solr_event_subscriber');
    $this->assertInstanceOf(SolrEventSubscriber::class, $subscriber);
  }

}
