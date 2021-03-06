<?php

final class DivinerLiveBook extends DivinerDAO
  implements PhabricatorPolicyInterface {

  protected $name;
  protected $viewPolicy;
  protected $configurationData = array();

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'configurationData' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
        'name' => array(
          'columns' => array('name'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getConfig($key, $default = null) {
    return idx($this->configurationData, $key, $default);
  }

  public function setConfig($key, $value) {
    $this->configurationData[$key] = $value;
    return $this;
  }

  public function generatePHID() {
    return PhabricatorPHID::generateNewPHID(
      DivinerBookPHIDType::TYPECONST);
  }

  public function getTitle() {
    return $this->getConfig('title', $this->getName());
  }

  public function getShortTitle() {
    return $this->getConfig('short', $this->getTitle());
  }

  public function getPreface() {
    return $this->getConfig('preface');
  }

  public function getGroupName($group) {
    $groups = $this->getConfig('groups', array());
    $spec = idx($groups, $group, array());
    return idx($spec, 'name', $group);
  }

/* -(  PhabricatorPolicyInterface  )----------------------------------------- */

  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return PhabricatorPolicies::getMostOpenPolicy();
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return false;
  }

  public function describeAutomaticCapability($capability) {
    return null;
  }

}
