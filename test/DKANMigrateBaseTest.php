<?php
class DKANMigrateBaseTest  extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        migrate_static_registration();
    }

    public function getNodeByTitle($title) {
      $query = new EntityFieldQuery();

       $entities = $query->entityCondition('entity_type', 'node')
        ->propertyCondition('title', $title)
        ->range(0,1)
        ->execute();

        if (!empty($entities['node'])) {
          $node = node_load(array_shift(array_keys($entities['node'])));
        }
        return $node;
    }



    public function testCKANResourceImport()
    {
      $migration = Migration::getInstance('ckan_dataset_migrate_base_example_resources');
      var_dump($migration->getGroup());
      $result = $migration->processImport();
      $this->assertEquals(Migration::RESULT_COMPLETED, $result);

      $migration1 = Migration::getInstance('ckan_dataset_migrate_base_example');
      $result = $migration1->processImport();
      $this->assertEquals(Migration::RESULT_COMPLETED, $result);

      $wisc_node = $this->getNodebyTitle('Wisconsin Polling Places');
      var_dump($wisc_node);
    }

    public function testCKANDatasetImport()
    {
      //$migration = Migration::getInstance('ckan_dataset_migrate_base_example');
      //$result = $migration->processImport();
      //var_dump($result);
      //$this->assertEquals(4, $result);
    }
}
/**
    // Test rollback
    $rawnodes = node_load_multiple(FALSE, array('type' => 'migrate_example_producer'), TRUE);
    $this->assertEqual(count($rawnodes), 0, t('All nodes deleted'));
    $count = db_select('migrate_map_wineproducerxml', 'map')
              ->fields('map', array('sourceid1'))
              ->countQuery()
              ->execute()
              ->fetchField();
    $this->assertEqual($count, 0, t('Map cleared'));
    $count = db_select('migrate_message_wineproducerxml', 'msg')
              ->fields('msg', array('sourceid1'))
              ->countQuery()
              ->execute()
              ->fetchField();
    $this->assertEqual($count, 0, t('Messages cleared'));
*/
