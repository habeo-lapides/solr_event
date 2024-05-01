<?php

namespace Drupal\solr_event\EventSubscriber;

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
   */

  /**
   * Public function __construct(FileSystemInterface $file_system, FileRepository $file_repository)
   */
  public function __construct(FileSystemInterface $file_system) {
    $this->fileSystem = $file_system;
    // $this->fileRepository = $file_repository;
  }

  /**
   *
   */
  public function writeToFile($query) {

    $path = 'private://solr/';
    // If ($this->fileSystem->prepareDirectory($path, FileSystemInterface::CREATE_DIRECTORY)) {.
    $filename = 'solr_log.txt';
    $fullPath = $path . $filename;
    $fileRepository = \Drupal::service('file.repository');
    // $file = $fileRepository->writeData($fullPath, $query);
    $file = $fileRepository->writeData($fullPath, $query);

    dpm($path);
    dpm($fullPath);
    dpm($query);
    dpm($files);

  }

    /**
     * {@inheritdoc}
     */
    public function findCombinations($words) {
      $combinations = [];
      // Iterate through each word
      foreach ($words as $word) {
          // Get length of word
          $length = strlen($word);
          // Generate combinations for each word
          for ($i = 0; $i < $length; $i++) {
              for ($j = $i + 1; $j <= $length; $j++) {
                  // Extract substring (combination) from the word
                  $combination = substr($word, $i, $j - $i);
                  // Add combination to the list if not already present
                  if (!in_array($combination, $combinations)) {
                      $combinations[] = $combination;
                  }
              }
          }
      }
      return $combinations;
  }

    /**
     * {@inheritdoc}
     */
  public function findValidWords($combinations, $dictionaryFile) {

    $validWords = [];
    
    $file = fopen($dictionaryFile, 'r');

    if ($file) {
      while (!feof($file)) {
        $dictionaryWord = trim(fgets($file));
        foreach ($combinations as $combination) {
          if (strlen($combination) > 3 &&
              str_contains($dictionaryWord, $combination)) {
            $validWords[] = $combination;
        }
      }
    }
  }

    return $validWords;
  }

    /**
     * {@inheritdoc}
     */
    public function fileIntoArray($filename) {
      $words = [];
      $file = fopen($filename, 'r');
      if ($file) {
        while (!feof($file)) {
          $lineWords = explode(" ", $line);
          foreach ($lineWords as $word) {
            $word = trim($word);
            if (!empty($word)) {
              $words[] = $word;
            }
          }
        }
      }
      fclose($file);
      return $words;
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
    $event = $event->getSolariumQuery();

    // Get the FilterQuery objects.
    $filterQueries = $event->getFilterQueries();

    foreach ($filterQueries as $key => $filter_query) {
      if ($key == 'filters_0') {

        $reflectionClass = new \ReflectionClass($filter_query);
        $optionsProperty = $reflectionClass->getProperty('options');
        $optionsProperty->setAccessible(TRUE);
        $options = $optionsProperty->getValue($filter_query);

        $pattern = '/\"(.*?)\"/';

        preg_match($pattern, $options['query'], $matches);

        $match_word = preg_replace("/[^A-Za-z0-9 ]/", '', $matches[0]);
           
        if (str_word_count($match_word) == 1) {
          $words[0] = $match_word;
        }
         else {
          $words = preg_split('\s+', strtolower($match_word));
         }


        // $this->writeToFile($matches[0]);
        if ($_ENV['DEPLOY_NAME'] == 'local') {
          $dictionaryFile = $_ENV['PWD'] . '/web/sites/default/files/words_dictionary.txt';
        }
        else {
          $dictionaryFile = '/files/words_dictionary.txt';
        }

        // Find combinations of letters in the input words
        $combinations = $this->findCombinations($words);

        $validWords = $this->findValidWords($combinations, $dictionaryFile);

        $searchWords = array_merge($words, $validWords);
   
      }

    }

    dpm("Word Variations:");
    dpm(implode(" ", $searchWords));

    // $solarium_query = $event->getSolariumQuery();
    // $event->addParam('fq', '(tm_X3b_en_body:("' . implode(" ", $searchWords) . '") tm_X3b_und_body:("' . implode(" ", $searchWords) . '")');

  }

}
