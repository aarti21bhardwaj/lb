<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;

/**
 * GradeContexts shell command.
 */
class GradeContextsShell extends Shell
{

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }

    public function mapping(){

        $this->loadModel('Grades');
        $grades = $this->Grades->find()->all();
        
        $this->loadModel('Contexts');
        $contexts = $this->Contexts->find()->all();
        $data = [];
        foreach ($grades as $grade) {
            foreach ($contexts as $context) {
                $data[] = [
                            'grade_id' => $grade->id,
                            'context_id' => $context->id
                          ];
            }
        }

        $connection = ConnectionManager::get('default');
        $response = $connection->transactional(function () use ($data){
            if(!empty($data)){
                $this->loadModel('GradeContexts');
                $gradeContexts = $this->GradeContexts->newEntities($data);
                $gradeContexts = $this->GradeContexts->patchEntities($gradeContexts, $data);
                
                if(!$this->GradeContexts->saveMany($gradeContexts)){
                  $this->out('Grade Contexts could not be saved.');
                  print_r($gradeContexts);
                }
             
                $this->out('Grade Contexts has been saved.');
            }
        });
    }
}
