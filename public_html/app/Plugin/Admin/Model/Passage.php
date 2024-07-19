<?php

class Passage extends AppModel
{
    public $validationDomain = 'validation';
    public $actsAs = array('search-master.Searchable');
    public $filterArgs = array('keyword' => array('type' => 'like', 'field' => 'Passage.passage'));

    public function beforeSave($options = array())
    {
        $passage = array();
        $Language = ClassRegistry::init('Language');
        $languageArr = $Language->find('all');
        foreach ($languageArr as $item) {
            $languageId = $item['Language']['id'];
            $passage[] = $this->data['Passage']["passage_$languageId"];
        }
        $this->data['Passage']['passage'] = implode("%^&", $passage);
        return true;
    }
}

?>