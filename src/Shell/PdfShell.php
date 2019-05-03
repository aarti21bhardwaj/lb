<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Collection\Collection;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Mailer\Email;

/**
 * Pdf shell command.
 */
class PdfShell extends Shell
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


    public function getAndRunAllPendingTasks(){
         $this->loadModel('CronJobs');
         $cronJobData = $this->CronJobs->find()->where(['in_process' => 1])
                                               ->all()
                                               ->toArray();

         if($cronJobData){
            echo "The request is already in process so will not continue";
            return false;
         }

         $cronJobs = $this->CronJobs->find()->where(['status' => 1])
                                            ->all();
                                            
         
         
         $cronData = $cronJobs->toArray();
         if(!$cronData){
            echo "No record found, Empty table";
            return false;
         }                              

         $cronJobIds = $cronJobs->extract('id')->toArray();

         $this->CronJobs->updateAll(['in_process' => 1], ['id IN' => $cronJobIds]);
         foreach ($cronData as $value) {
            if($value->method_name == 'generateLinksForPdf') {
                $this->_generateLinksForPdf($value);
            }

             if($value->method_name == 'sendPdfLinkInEmail') {
                $this->_sendPdfLinkInEmail($value);
            }
         }
    }

    private function _generateLinksForPdf($data){
        // pr($data->meta['email']); die;
        $this->loadModel('ReportTemplates');
        $reportTemplateId = $data->meta['report_template_id']; 
        
        $reportTemplate = $this->ReportTemplates->findById($reportTemplateId)
                                                ->contain(['ReportSettings','ReportingPeriods.Terms'])
                                                ->first();

        // pr($reportTemplate); die;
        $reportSettingsWithCourses = new Collection($reportTemplate->report_settings);  
        $selectedCourses = $reportSettingsWithCourses->extract('course_id')->toArray();

        $this->loadModel('Sections');

        $studentData = $this->Sections->find()->contain(['SectionStudents.Students' => function($q){
                                            return $q->where(['is_active' => 1]);
                                          }])
                                              ->where([
                                                        'term_id' => $reportTemplate->reporting_period->term_id,
                                                        'course_id IN' => $selectedCourses,
                                              ])
                                              ->all()
                                              ->extract('section_students.{*}.student')
                                              ->toArray();

        // pr($studentData); die;
        $studentData = array_unique($studentData); 
                                                                                         
        $studentList = (new Collection($studentData))->indexBy('id')->toArray();

        $url = Configure::read('application.baseUrl');
        $studentReportLink = [];

        $fileName = 'file'.$data->id;

        $fileOpen = fopen('pdfs/preProcessed/lists/'.$fileName, 'w');
        $fileOpen2 = fopen('pdfs/preProcessed/passwords/'.$fileName, 'w');


        fputcsv($fileOpen,['File Name', 'Link']);	
	      $this->loadModel('Users');
        
        $wkhtmltopdfConfig = Configure::read('reportTemplateConfig');
        $reportTemplateNames = Configure::read('reportTemplateNames');
        $reportTemplateName = isset($reportTemplateNames[$reportTemplateId]) ? $reportTemplateNames[$reportTemplateId] : $reportTemplateId; 
        foreach ($studentList as $key => $value) {

        $footerHash = [
          'student_name' => $value->full_name,
          'student_legacy_id' => $value->legacy_id
        ]; 

        if(isset($wkhtmltopdfConfig[$reportTemplateId])){
          $reportTemplateConfig = $wkhtmltopdfConfig[$reportTemplateId];

          if(isset($reportTemplateConfig['footer-right'])){
              $reportTemplateConfig['footer-right'] = $this->ReportTemplates->substitute($reportTemplateConfig['footer-right'], $footerHash);
          }
          if(isset($reportTemplateConfig['footer-center'])){
              $reportTemplateConfig['footer-center'] = $this->ReportTemplates->substitute($reportTemplateConfig['footer-center'], $footerHash);
          }

          if(isset($reportTemplateConfig['footer-left'])){
              $reportTemplateConfig['footer-left'] = $this->ReportTemplates->substitute($reportTemplateConfig['footer-left'], $footerHash);
          }

          $reportTemplateConfig = (new Collection($reportTemplateConfig))->map(function($settingValue, $settingName){
            if(strrpos($settingValue, ' ')){
               return "--".$settingName." '".$settingValue."'";
            }
            return "--".$settingName." ".$settingValue;

          })->toArray();
          $reportTemplateConfig = implode(' ',$reportTemplateConfig);
        }else{
          $reportTemplateConfig = "";
        }
	 $reportTemplateConfig = $reportTemplateConfig." --print-media-type"; 

          try{
                $grade = $this->Users->getStudentGrade($value->legacy_id, true);
          } catch (\Exception $e) {
            $grade = 'DNF';
          }


          $value->legacy_id = trim(strToLower($value->legacy_id));
          $tempName = "Grade_".$grade."_".$reportTemplateName."_".$value->last_name."_".$value->first_name;   
          $studentReportLink = [
                                   $tempName,           
                                   'xvfb-run -a /usr/local/bin/wkhtmltopdf '.$reportTemplateConfig.' '.$url.'/pdf/report/'.$key.'/'.$reportTemplateId.'/0 '.$tempName.'.pdf'
                                ];
          fputs($fileOpen, implode($studentReportLink, ',')."\n");

	  // fputcsv($fileOpen, $studentReportLink);
        }

        echo "Csv file created";
        fwrite($fileOpen2, $data->meta['password']);
        fclose($fileOpen);
        fclose($fileOpen2);

         $newData = [
                    'shell_name' => 'Pdf', 
                    'method_name' => 'sendPdfLinkInEmail',
                    'status' => 1,
                    'meta' => [
                                'email' => $data->meta['email'],
                                'fileName' => $fileName.'.zip'
                              ],

                    'in_process' => 0
                ];

        $cronJobs = $this->CronJobs->newEntity();
        $cronJobs = $this->CronJobs->patchEntity($cronJobs, $newData);
        // pr($cronJobs); die;
        if(!$this->CronJobs->save($cronJobs)){
            echo "Something went wrong in updating data";
        }else{
            echo "New Entity created for sending email";
        }

        $cronJobData = $this->CronJobs->find()->where(['id' => $data->id])->first();
        $data = [
                    'status'  => 0,
                    'in_process' => 0
                ];
        $cronJobData = $this->CronJobs->patchEntity($cronJobData, $data);

        if(!$this->CronJobs->save($cronJobData)){
            echo "Something went wrong in updating data";
        }

        echo $fileName;
        // return $fileName;
    }


    private function _sendPdfLinkInEmail($data = null){
        $this->loadModel('CronJobs');
        $cronJobData = $this->CronJobs->find()->where(['id' => $data->id])->first();
        $file = WWW_ROOT.'pdfs/'.$data->meta["fileName"];
        // pr($file); die;
        if(file_exists($file)){
            $url = Configure::read('application.baseUrl').'/pdfs/'.$data->meta["fileName"];
            $email = new Email('default');
            $email->to($data->meta["email"])
                  ->subject('Pdfs requested by you are ready to download')
                  ->send("Click on link below to download the pdf \n".$url);

            $data = [
                      'status' => 0,
                      'in_process' => 0
                    ];
        }else{
            echo "File ".$data->meta["fileName"].' not found';
            $data = [
                      'in_process' => 0
                    ];
        }

        $cronJobData = $this->CronJobs->patchEntity($cronJobData, $data);

        if(!$this->CronJobs->save($cronJobData)){
            echo "Something went wrong in updating data";
        }
    }

}
