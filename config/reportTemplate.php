<?php
use Cake\Core\Configure;
//If a menu has children, then the link for the menu must always be #
//All links must be in the form of ['controller' => 'ControllerName', 'action' =>'action name' ]
return [
          'reportTemplateConfig' => [
                                      '1' => [
                                                'zoom' => '0.75',
                                                'margin-right' => '2mm',
                                                'margin-left' => '2mm',
                                             ],

                                      '2' => [
                                                'margin-top' => '3mm',
                                                'margin-bottom' => '8mm',
                                                'margin-right' => '3mm',
                                                'margin-left' => '2mm',
                                                'zoom' => '1',
                                                'footer-center' => 'THE ANGLO-AMERICAN SCHOOL OF MOSCOW | +7 (495) 231-4488 | 1 BEREGOVAYA STREET | 125367 MOSCOW, RUSSIA',
                                                'footer-font-size' => '8',
                                             ],
                                      '3' => [
                                                'margin-bottom' => '14mm',
                                                'margin-top' => '5mm',
                                                'margin-right' => '10mm',
                                                'margin-left' => '10mm',
                                                'footer-left' => 'Anglo American School of Moscow',
                                                'footer-font-size' => '10'
                                             ],
                                      '4' => [
                                                'margin-bottom' => '13mm',
                                                'margin-top' => '7mm',
                                                'margin-right' => '10mm',
                                                'margin-left' => '10mm',
						'zoom' => '1',
                                                'footer-left' => 'Anglo American School of Moscow',
                                                'footer-font-size' => '10',
                                                'footer-right' => '{{student_name}} ({{student_legacy_id}}) Page [page] of [toPage]'
                                             ]
                                    ],
          'reportTemplateNames' => [
                                      '1' => '201718MSQ3',
                                      '2' => '201718MSQ4',
                                      '3' => '201718ESSEM2',
                                      '4' => '201718ESSEM2'
                                   ] 
       ];
?>
