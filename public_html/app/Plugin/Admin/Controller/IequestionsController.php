<?php
ini_set('max_execution_time', 900);

class IequestionsController extends AdminAppController
{
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session', 'PhpExcel.PhpExcel');

    public function index()
    {
        try {
            $subjectId = null;
            $topicId = null;
            $this->loadModel('Subject');
            $this->loadModel('Group');
            $this->set('subject_id', $this->CustomFunction->getSubjectList($this->userGroupWiseId));
            $this->set('group_id', $this->Group->find('list', array('fields' => array('id', 'group_name'), 'conditions' => array('Group.id' => $this->userGroupWiseId))));
            if ($this->request->data) {
                $subjectId = $this->request->data['Iequestion']['subject_id'];
                $topicId = $this->request->data['Iequestion']['topic_id'];
            }
            $this->loadModel('Topic');
            $topic = $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $subjectId), 'order' => array('name' => 'asc')));
            $this->set('topic', $topic);
            $this->loadModel('Stopic');
            $stopic = $this->Stopic->find('list', array('conditions' => array('Stopic.topic_id' => $topicId), 'order' => array('name' => 'asc')));
            $this->set('stopic', $stopic);
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    public function exportquestions()
    {
        try {
            $subjectId = null;
            $topicId = null;
            $this->loadModel('Subject');
            $this->set('subject_id', $this->CustomFunction->getSubjectList($this->userGroupWiseId));
            if ($this->request->data) {
                $subjectId = $this->request->data['Iequestion']['subject_id'];
                $topicId = $this->request->data['Iequestion']['topic_id'];
            }
            $this->loadModel('Topic');
            $topic = $this->Topic->find('list', array('conditions' => array('Topic.subject_id' => $subjectId), 'order' => array('name' => 'asc')));
            $this->set('topic', $topic);
            $this->loadModel('Stopic');
            $stopic = $this->Stopic->find('list', array('conditions' => array('Stopic.topic_id' => $topicId), 'order' => array('name' => 'asc')));
            $this->set('stopic', $stopic);

        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
        }
    }

    public function import()
    {
        try {
            if ($this->request->is('post')) {
                if (is_array($this->request->data['QuestionGroup']['group_name'])) {
                    if (strlen($this->request->data['Iequestion']['subject_id']) > 0) {
                        $fixed = array('subject_id' => $this->request->data['Iequestion']['subject_id'], 'topic_id' => $this->request->data['Iequestion']['topic_id'], 'stopic_id' => $this->request->data['Iequestion']['stopic_id']);
                        $groupName = $this->request->data['QuestionGroup']['group_name'];
                        $filename = null;
                        $extension = null;
                        $extension = pathinfo($this->request->data['Iequestion']['file']['name'], PATHINFO_EXTENSION);
                        if ($extension == "xls") {
                            if (!empty($this->request->data['Iequestion']['file']['tmp_name']) && is_uploaded_file($this->request->data['Iequestion']['file']['tmp_name'])) {
                                $filename = basename($this->request->data['Iequestion']['file']['name']);
                                $tmpPath = APP . DS . 'tmp' . DS . 'xls' . DS . $filename;
                                move_uploaded_file($this->data['Iequestion']['file']['tmp_name'], APP . DS . 'tmp' . DS . 'xls' . DS . $filename);
                                $this->PhpExcel->loadWorksheet();
                                $rowData = $this->PhpExcel->importData('Excel5', $tmpPath);
                                if ($this->importInsert($rowData, $groupName, $fixed)) {
                                    if (file_exists($tmpPath))
                                        unlink($tmpPath);
                                    $this->Session->setFlash(__('Questions imported successfully'), 'flash', array('alert' => 'success'));
                                    return $this->redirect(array('action' => 'index'));
                                } else {
                                    if (file_exists($tmpPath))
                                        unlink($tmpPath);
                                    $this->Session->setFlash(__('File not uploaded'), 'flash', array('alert' => 'danger'));
                                    return $this->redirect(array('action' => 'index'));
                                }
                            } else {
                                $this->Session->setFlash(__('File not uploaded'), 'flash', array('alert' => 'danger'));
                                return $this->redirect(array('action' => 'index'));
                            }
                        } else {
                            $this->Session->setFlash(__('Only XLS File supported'), 'flash', array('alert' => 'danger'));
                            return $this->redirect(array('action' => 'index'));
                        }
                    } else {
                        $this->Session->setFlash(__('Please Select Subject'), 'flash', array('alert' => 'danger'));
                        return $this->redirect(array('action' => 'index'));
                    }
                } else {
                    $this->Session->setFlash(__('Please Select any Group'), 'flash', array('alert' => 'danger'));
                    return $this->redirect(array('action' => 'index'));
                }
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function importInsert($rowData, $groupArr, $fixed)
    {

        foreach ($rowData as $dataValue) {
            $dataValue = array_shift($dataValue);
			if ($dataValue[1] == "T") {
				$trueFalse = ucfirst(strtolower($dataValue[14]));
				if ($trueFalse == "1") {
					$trueFalse = "True";
				} else {
					$trueFalse = "False";
				}
			} else {
				$trueFalse = NULL;
			}

            if ($dataValue[0] == "E")
                $dataValue[0] = 1;
            elseif ($dataValue[0] == "M")
                $dataValue[0] = 2;
            elseif ($dataValue[0] == "H")
                $dataValue[0] = 3;
            else
                $dataValue[0] = 1;

            if ($dataValue[1] == "M")
                $dataValue[1] = 1;
            elseif ($dataValue[1] == "T")
                $dataValue[1] = 2;
            elseif ($dataValue[1] == "F")
                $dataValue[1] = 3;
            elseif ($dataValue[1] == "S")
                $dataValue[1] = 4;
            else
                $dataValue[1] = 1;

            if (isset($dataValue[16])) {
                $id = $dataValue[16];
            } else {
                $id = null;
            }

            $recordArr = array('id' => $id, 'diff_id' => $dataValue[0], 'qtype_id' => $dataValue[1], 'question' => $dataValue[2], 'option1' => $dataValue[3], 'option2' => $dataValue[4],
                'option3' => $dataValue[5], 'option4' => $dataValue[6], 'option5' => $dataValue[7], 'option6' => $dataValue[8], 'marks' => $dataValue[9],
                'negative_marks' => $dataValue[10], 'hint' => $dataValue[11], 'explanation' => $dataValue[12], 'answer' => $dataValue[13], 'true_false' => $trueFalse, 'fill_blank' => $dataValue[15]);

            $recordArr = Set::merge($recordArr, $fixed);
            if ($this->Iequestion->save($recordArr)) {
                $this->loadModel('QuestionGroup');
                $questionId = $this->Iequestion->id;
                if ($id != null)
                    $this->QuestionGroup->deleteAll(array('QuestionGroup.question_id' => $questionId));
                $QuestionGroup = array();
                foreach ($groupArr as $groupId) {
                    $QuestionGroup[] = array('question_id' => $questionId, 'group_id' => $groupId);
                }
                $this->QuestionGroup->create();
                $this->QuestionGroup->saveAll($QuestionGroup);
            } else {
                $this->Iequestion->rollback('Iequestion');
                $this->QuestionGroup->rollback('QuestionGroup');
                return false;
            }
            $this->Iequestion->commit();
            $this->QuestionGroup->commit();
        }
        return true;
    }

    public function export()
    {
        $this->layout = null;
        $this->autoRender = false;
        try {
            if (strlen($this->request->data['Iequestion']['subject_id']) == 0) {
                $this->Session->setFlash('Invalid Post!', 'flash', array('alert' => 'danger'));
                return $this->redirect(array('action' => 'exportquestions'));
            }
            $data = $this->exportData($this->request->data['Iequestion']['subject_id'], $this->request->data['Iequestion']['topic_id'], $this->request->data['Iequestion']['stopic_id']);
            $this->PhpExcel->createWorksheet();
            $this->PhpExcel->addTableRow($data);
            $this->PhpExcel->output('Question', $this->siteName, 'question.xls', 'Excel2007');
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    private function exportData($subjectId, $topicId, $stopicId)
    {
        try {
            $this->Iequestion->UserWiseGroup($this->userGroupWiseId);
            $post = $this->Iequestion->find('all', array('joins' => array(array('table' => 'question_groups', 'type' => 'INNER', 'alias' => 'QuestionGroup', 'conditions' => array('Iequestion.id=QuestionGroup.question_id')),
            ),
                'conditions' => array('QuestionGroup.group_id' => $this->userGroupWiseId, 'Iequestion.subject_id' => $subjectId, 'Iequestion.topic_id' => $topicId, 'Iequestion.stopic_id' => $stopicId),
                'fields' => array(
                    'Iequestion.id', 'Iequestion.qtype_id', 'Iequestion.subject_id', 'Iequestion.topic_id', 'Iequestion.stopic_id', 'Iequestion.diff_id', 'Iequestion.question', 'Iequestion.diff_id', 'Iequestion.option1',
                    'Iequestion.option2', 'Iequestion.option3', 'Iequestion.option4', 'Iequestion.option5', 'Iequestion.option6', 'Iequestion.marks', 'Iequestion.negative_marks', 'Iequestion.hint', 'Iequestion.explanation',
                    'Iequestion.answer', 'Iequestion.true_false', 'Iequestion.fill_blank', 'Iequestion.status'
                ),
                'group' => array(
                    'Iequestion.id', 'Iequestion.qtype_id', 'Iequestion.subject_id', 'Iequestion.topic_id', 'Iequestion.stopic_id', 'Iequestion.diff_id', 'Iequestion.question', 'Iequestion.diff_id', 'Iequestion.option1',
                    'Iequestion.option2', 'Iequestion.option3', 'Iequestion.option4', 'Iequestion.option5', 'Iequestion.option6', 'Iequestion.marks', 'Iequestion.negative_marks', 'Iequestion.hint', 'Iequestion.explanation',
                    'Iequestion.answer', 'Iequestion.true_false', 'Iequestion.fill_blank', 'Iequestion.status'
                ),
            ));
            $data = $this->showQuestionData($post);
            return $data;
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage(), 'flash', array('alert' => 'danger'));
            return $this->redirect(array('action' => 'index'));
        }

    }

    private function showQuestionData($post)
    {
        $this->loadModel('Subject');
        $this->loadModel('Topic');
        $this->loadModel('Stopic');
        $this->loadModel('Qtype');
        $this->loadModel('Diff');

        $subjectArr= $this->Subject->find('list', array('fields' => array('Subject.id', 'Subject.subject_name')));
        $topicArr= $this->Topic->find('list');
        $stopicArr= $this->Stopic->find('list');
		$qtypeArr= $this->Qtype->find('list', array('fields' => array('id', 'type')));
		$diffArr= $this->Diff->find('list', array('fields' => array('id', 'type')));

        $showData = array(array('Difficulty Level', 'Question Type', 'Question', 'Option1', 'option2', 'option3',
            'Option4', 'Option5', 'Option6', 'Marks', 'Negative Marks', 'Hint', 'Explanation', 'Correct Answer', 'True & False', 'Fill in the blanks', 'Id', 'Groups', 'Subject', 'Topic', 'Sub Topic'));
        foreach ($post as $rank => $value) {
            $showData[] = array(
                $diffArr[$value['Iequestion']['diff_id']],
                $qtypeArr[$value['Iequestion']['qtype_id']],
                $value['Iequestion']['question'],
                $value['Iequestion']['option1'],
                $value['Iequestion']['option2'],
                $value['Iequestion']['option3'],
                $value['Iequestion']['option4'],
                $value['Iequestion']['option5'],
                $value['Iequestion']['option6'],
                $value['Iequestion']['marks'],
                $value['Iequestion']['negative_marks'],
                $value['Iequestion']['hint'],
                $value['Iequestion']['explanation'],
                $value['Iequestion']['answer'],
                $value['Iequestion']['true_false'],
                $value['Iequestion']['fill_blank'],
                $value['Iequestion']['id'],
                $this->CustomFunction->showGroupName($value['Group']),
                $subjectArr[$value['Iequestion']['subject_id']],
                $topicArr[$value['Iequestion']['topic_id']],
                $stopicArr[$value['Iequestion']['stopic_id']]
            );
        }
        return $showData;
    }

    public function download()
    {
        $this->viewClass = 'Media';
        $params = array(
            'id' => 'sample-question.xls',
            'name' => 'SampleQuestion',
            'download' => true,
            'extension' => 'xls',
            'mimeType' => array('xls' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
            'path' => APP . 'tmp' . DS . 'download' . DS
        );
        $this->set($params);
    }
}
