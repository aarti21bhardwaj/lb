<?php
use Migrations\AbstractSeed;

/**
 * ScaleValues seed.
 */
class ScaleValuesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    [ 
                      'name'    => 'Not yet assessed',
                      'label' => '',
                      'value' => 0,
                      'scale_id' => 1,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'color' => 'black'
                      
                    ],
                    [ 
                      'name'    => 'Insufficient Evidence',
                      'label' => '',
                      'value' => 1,
                      'scale_id' => 1,
                      'sort_order'   => 1,
                      'numeric_value'   => NULL,
                      'color' => 'black'
                      
                    ],
                    [ 
                      'name'    => 'Not meeting',
                      'label' => 'Not meeting',
                      'value' => 2,
                      'scale_id' => 1,
                      'sort_order'   => 2,
                      'numeric_value'   => 0.5,
                      'color' => 'red'
                      
                    ],
                    [ 
                      'name'    => 'Not meeting',
                      'label' => '',
                      'value' => 3,
                      'scale_id' => 1,
                      'sort_order'   => 3,
                      'numeric_value'   => 1.0,
                      'color' => 'red'
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'label' => 'Approaching',
                      'value' => 4,
                      'scale_id' => 1,
                      'sort_order'   => 4,
                      'numeric_value'   => 1.5,
                      'color' => 'yellow'              
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'label' => '',
                      'value' => 5,
                      'scale_id' => 1,
                      'sort_order'   => 5,
                      'numeric_value'   => 2.0,
                      'color' => 'yellow'
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'label' => '',
                      'value' => 6,
                      'scale_id' => 1,
                      'sort_order'   => 6,
                      'numeric_value'   => 2.5,
                      'color' => 'yellow'
                    ],
                    [ 
                      'name'    => 'Meeting',
                      'label' => 'Meeting',
                      'value' => 7,
                      'scale_id' => 1,
                      'sort_order'   => 7,
                      'numeric_value'   => 3.0,
                      'is_default' => 1,
                      'color' => 'green'
                    ],
                    [ 
                      'name'    => 'Meeting',
                      'label' => '',
                      'value' => 8,
                      'scale_id' => 1,
                      'sort_order'   => 8,
                      'numeric_value'   => 3.5,
                      'color' => 'green'
                    ],
                    [ 
                      'name'    => 'Exemplary',
                      'label' => 'Exemplary',
                      'value' => 9,
                      'scale_id' => 1,
                      'sort_order'   => 9,
                      'numeric_value'   => 4.0,
                      'color' => 'blue'
                    ],



                    [ 
                      'name'    => 'Not yet assessed',
                      'value' => 0,
                      'scale_id' => 2,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'color' => 'black'
                      
                    ],
                    [ 
                      'name'    => 'Insufficient Evidence',
                      'value' => 1,
                      'scale_id' => 2,
                      'sort_order'   => 1,
                      'numeric_value'   => NULL,
                      'color' => 'black'
                      
                    ],
                    [ 
                      'name'    => 'Not meeting',
                      'value' => 2,
                      'scale_id' => 2,
                      'sort_order'   => 2,
                      'numeric_value'   => 1.0,
                      'color' => 'red'
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'value' => 3,
                      'scale_id' => 2,
                      'sort_order'   => 3,
                      'numeric_value'   => 2.0,
                      'color' => 'yellow'
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'value' => 4,
                      'scale_id' => 2,
                      'sort_order'   => 4,
                      'numeric_value'   => 2.5,
                      'color' => 'yellow'                 
                    ],
                    [ 
                      'name'    => 'Meeting',
                      'value' => 5,
                      'scale_id' => 2,
                      'sort_order'   => 5,
                      'numeric_value'   => 3.0,
                      'is_default' => 1,
                      'color' => 'green'
                    ],
                    [ 
                      'name'    => 'Exemplary',
                      'value' => 6,
                      'scale_id' => 2,
                      'sort_order'   => 6,
                      'numeric_value'   => 4.0,
                      'color' => 'blue'
                    ],


                    [ 
                      'name'    => 'Insufficient Evidence',
                      'value' => 1,
                      'scale_id' => 3,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'description' => 'Insufficient evidence is available to accurately determine the learner’s progress toward attainment of the learning goals which may be due to late enrollment, attendance, or inconsistent completion of significant coursework.',
                      'color' => 'black'
                    ],
                    [ 
                      'name'    => 'Not Meeting',
                      'value' => 2,
                      'scale_id' => 3,
                      'sort_order'   => 1,
                      'numeric_value'   => 1.0,
                      'description' => 'Learner was not able to adequately demonstrate and communicate a sufficient understanding or application of the learning goals, requiring immediate intervention.',
                      'color' => 'red'
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'value' => 3,
                      'scale_id' => 3,
                      'sort_order'   => 2,
                      'numeric_value'   => 2.0,
                      'description' => 'Learner demonstrates progress towards meeting targeted learning outcomes but may require various degrees of additional instruction, teacher support and time in order to show understanding and application of significant concepts, knowledge and skills.',
                      'color' => 'yellow'
                    ],
                    [ 
                      'name'    => 'Meeting the Standard',
                      'value' => 4,
                      'scale_id' => 3,
                      'sort_order'   => 3,
                      'numeric_value'   => 3.0 ,                  
                      'description' => 'Learner independently demonstrates and communicates a clear understanding of targeted learning outcomes, including proficient application of significant concepts, knowledge and skills.',
                      'color' => 'green'
                    ],
                    [ 
                      'name'    => 'Exemplary',
                      'value' => 5,
                      'scale_id' => 3,
                      'sort_order'   => 4,
                      'numeric_value'   => 4.0,
                      'is_default' => 1,
                      'description' => 'Learner demonstrates and communicates a high-level understanding of learning outcomes, which may include an application of significant concepts, knowledge and skills in real world contexts, across transdisciplinary opportunities and/or within a set of curricular standards beyond those targeted.',
                      'color' => 'blue'
                    ],



                    [ 
                      'name'    => 'Insufficient Evidence',
                      'value' => 1,
                      'scale_id' => 4,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'color' => 'black'
                    ],
                    [ 
                      'name'    => 'Developing',
                      'value' => 2,
                      'scale_id' => 4,
                      'sort_order'   => 1,
                      'numeric_value'   => 1.0,
                      'color' => 'red'
                      
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'value' => 3,
                      'scale_id' => 4,
                      'sort_order'   => 2,
                      'numeric_value'   => 2.0,
                      'color' => 'yellow'
                    ],
                    [ 
                      'name'    => 'Target',
                      'value' => 4,
                      'scale_id' => 4,
                      'sort_order'   => 3,
                      'numeric_value'   => 3.0,
                      'color' => 'green'               
                    ],
                    [ 
                      'name'    => 'Extending',
                      'value' => 5,
                      'scale_id' => 4,
                      'sort_order'   => 4,
                      'numeric_value'   => 4.0,
                      'is_default' => 1,
                      'color' => 'blue'
                    ],

                    // AAS Scale

                    [ 
                      'name'    => 'Insufficient Evidence',
                      'value' => 1,
                      'scale_id' => 5,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'color' => 'black',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'insufficient_evidence.jpg'
                    ],
                    [ 
                      'name'    => 'Not Meeting',
                      'value' => 2,
                      'scale_id' => 5,
                      'sort_order'   => 1,
                      'numeric_value'   => 1.0,
                      'color' => 'red',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'not_meeting.jpg'
                      
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'value' => 3,
                      'scale_id' => 5,
                      'sort_order'   => 2,
                      'numeric_value'   => 2.0,
                      'color' => 'yellow',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'approaching.jpg'
                    ],
                    [ 
                      'name'    => 'Meeting the Standard',
                      'value' => 4,
                      'scale_id' => 5,
                      'sort_order'   => 3,
                      'numeric_value'   => 3.0,
                      'color' => 'green',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'meeting_the_standard.jpg'             
                    ],
                    [ 
                      'name'    => 'Extending',
                      'value' => 5,
                      'scale_id' => 5,
                      'sort_order'   => 4,
                      'numeric_value'   => 4.0,
                      'is_default' => 1,
                      'color' => 'blue',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'extending.jpg'
                    ],

                    // AAS Impact Scale
                    [ 
                      'name'    => 'Rarely',
                      'value' => 1,
                      'scale_id' => 6,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'color' => 'black',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'rarely.jpg'
                    ],
                    [ 
                      'name'    => 'Sometimes',
                      'value' => 2,
                      'scale_id' => 6,
                      'sort_order'   => 1,
                      'numeric_value'   => 1.0,
                      'color' => 'red',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'sometimes.jpg'
                      
                    ],
                    [ 
                      'name'    => 'Usually',
                      'value' => 3,
                      'scale_id' => 6,
                      'sort_order'   => 2,
                      'numeric_value'   => 2.0,
                      'color' => 'yellow',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'usually.jpg'
                    ],
                    [ 
                      'name'    => 'Consistently',
                      'value' => 4,
                      'scale_id' => 6,
                      'sort_order'   => 3,
                      'numeric_value'   => 3.0,
                      'color' => 'green',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'consistently.jpg'             
                    ],

                    //ISC Academic Scale
                    [ 
                      'name'    => 'Insufficient Evidence',
                      'value' => 0,
                      'scale_id' => 7,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'color' => 'black',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'insufficient_evidence.jpg',
                      'description' => "Insufficient evidence is available to accurately determine the learner's progress toward attainment of the learning goals which may be due to late enrollment, attendance, or inconsistent completion of significant coursework."
                    ],
                    [ 
                      'name'    => 'Not Meeting',
                      'value' => 1,
                      'scale_id' => 7,
                      'sort_order'   => 1,
                      'numeric_value'   => 1.0,
                      'color' => 'red',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'not_meeting.jpg',
                      'description' => "Learner was not able to adequately demonstrate and communicate a sufficient understanding or application of the learning goals, requiring immediate intervention."
                      
                    ],
                    [ 
                      'name'    => 'Approaching',
                      'value' => 2,
                      'scale_id' => 7,
                      'sort_order'   => 2,
                      'numeric_value'   => 2.0,
                      'color' => 'yellow',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'approaching.jpg',
                      'description' => "Learner demonstrates progress towards meeting targeted learning outcomes but may require various degrees of additional instruction, teacher support and time in order to show understanding and application of significant concepts, knowledge and skills."
                    ],
                    [ 
                      'name'    => 'Meeting the Standard',
                      'value' => 3,
                      'scale_id' => 7,
                      'sort_order'   => 3,
                      'numeric_value'   => 3.0,
                      'color' => 'green',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'meeting_the_standard.jpg',
                      'description' => "Learner independently demonstrates and communicates a clear understanding of targeted learning outcomes, including proficient application of significant concepts, knowledge and skills."             
                    ],
                    [ 
                      'name'    => 'Exemplary',
                      'value' => 4,
                      'scale_id' => 7,
                      'sort_order'   => 4,
                      'numeric_value'   => 4.0,
                      'is_default' => 1,
                      'color' => 'blue',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'extending.jpg',
                      'description' => "Learner demonstrates and communicates a high-level understanding of learning outcomes, which may include an application of significant concepts, knowledge and skills in real world contexts, across transdisciplinary opportunities and/or within a set of curricular standards beyond those targeted."
                    ],

                    //ISC Impact Scale
                    [ 
                      'name'    => 'Insufficient Evidence',
                      'value' => 0,
                      'scale_id' => 8,
                      'sort_order'   => 0,
                      'numeric_value'   => NULL,
                      'color' => 'black',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'insufficient_evidence.jpg'
                    ],
                    [ 
                      'name'    => 'Rarely',
                      'value' => 1,
                      'scale_id' => 8,
                      'sort_order'   => 1,
                      'numeric_value'   => 1.0,
                      'color' => 'black',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'rarely.jpg'
                    ],
                    [ 
                      'name'    => 'Sometimes',
                      'value' => 2,
                      'scale_id' => 8,
                      'sort_order'   => 2,
                      'numeric_value'   => 2.0,
                      'color' => 'red',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'sometimes.jpg'
                      
                    ],
                    [ 
                      'name'    => 'Usually',
                      'value' => 3,
                      'scale_id' => 8,
                      'sort_order'   => 3,
                      'numeric_value'   => 3.0,
                      'color' => 'yellow',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'usually.jpg'
                    ],
                    [ 
                      'name'    => 'Consistently',
                      'value' => 4,
                      'scale_id' => 8,
                      'sort_order'   => 4,
                      'numeric_value'   => 4.0,
                      'color' => 'green',
                      'image_path' => 'webroot/scale_images',
                      'image_name' => 'consistently.jpg'             
                    ],
                ];


        $table = $this->table('scale_values');
        $table->insert($data)->save();
    }
}