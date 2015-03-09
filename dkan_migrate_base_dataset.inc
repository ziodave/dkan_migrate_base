<?php

/**
 * @file
 * Migration Class for Datasets
 */

/**
 * Migrate CKAN dataset
 */
class MigrateCkanDatasetBase extends MigrateCkanBase {
  /**
   * Here we go.
   */
  public function __construct($arguments) {
    parent::__construct($arguments);
    $fields = $this->getCkanDatasetFields();

    $list_url = isset($arguments['list_url']) ? $arguments['list_url'] : 'package_list';
    $list_url = $this->endpoint . $list_url;
    $item_url = isset($arguments['item_url']) ? $arguments['item_url'] : 'package_show?id=:id';
    $item_url = $this->endpoint . $item_url;

    $this->highwaterField = array(
      'name' => 'revision_timestamp',
    );

    $this->source = new MigrateSourceList(new CKANListJSON($list_url),
          new CKANItemJSON($item_url, $fields), $fields);
    $this->map = new MigrateSQLMap(
            $this->machineName,
            array(
              'uuid' => array(
                'type' => 'varchar',
                'length' => 255,
                'not null' => TRUE,
                'description' => 'id',
              ),
            ),
            MigrateDestinationNode::getKeySchema()
        );

    $this->destination = new MigrateDestinationNode('dataset', array('text_format' => 'html'));

    $this->addDefaultDatasetMappings();

  }

  /**
   * Implements prepareRow.
   */
  public function prepareRow($row) {
    $this->datasetPrepareRow($row);
  }

  /**
   * Implements prepare.
   */
  public function prepare($node, stdClass $row) {
    $this->datasetPrepare($node, $row);
  }
}
