<?php
use Cake\Core\Configure;
//If a menu has children, then the link for the menu must always be #
//All links must be in the form of ['controller' => 'ControllerName', 'action' =>'action name' ]
return [ 'Menu' => 
                  [
                      
                   'Admin' =>  [
                    'Go To Learning Board' => [
                        'link' => '/teachers#/',
                        'class' => 'fa-list-alt',
                      ],
                   'Curriculum' => [
                        'link' => '#',
                        'class' => 'fa fa-sitemap',
                        'children' => [
                          'Curriculums' => [
                              'link' => '#',
                              'children' => [
                                    'View Curriculums' => [
                                        'link' => [
                                              'controller' => 'Curriculums',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Curriculum' => [
                                        'link' => [
                                                   'controller' => 'Curriculums',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                          'Learning Areas' => [
                              'link' => '#',
                              'children' => [
                                    'View Learning Areas' => [
                                        'link' => [
                                              'controller' => 'LearningAreas',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Learning Area' => [
                                        'link' => [
                                                   'controller' => 'LearningAreas',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Strands' => [
                              'link' => '#',
                              'children' => [
                                    'View Strands' => [
                                        'link' => [
                                              'controller' => 'Strands',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Strand' => [
                                        'link' => [
                                                   'controller' => 'Strands',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Standards' => [
                              'link' => '#',
                              'children' => [
                                    'View Standards' => [
                                        'link' => [
                                              'controller' => 'Standards',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Standard' => [
                                        'link' => [
                                                   'controller' => 'Standards',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            // 'Impacts' => [
                            //   'link' => '#',
                            //   'children' => [
                            //         'View Impacts' => [
                            //             'link' => [
                            //                   'controller' => 'Impacts',
                            //                   'action' => 'index'
                            //                 ],
                            //               ],
                            //           'Add Impact' => [
                            //             'link' => [
                            //                        'controller' => 'Impacts',
                            //                        'action' => 'add'
                            //                       ],
                            //               ] 
                            //       ] 
                            // ],
                            'Impact Categories' => [
                              'link' => '#',
                              'children' => [
                                    'View Impact Categories' => [
                                        'link' => [
                                              'controller' => 'ImpactCategories',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Impact Category' => [
                                        'link' => [
                                                   'controller' => 'ImpactCategories',
                                                   'action' => 'add'
                                                  ],
                                          ],
                                      'Impacts' => [
                                        'link' => [
                                                   'controller' => 'Impacts',
                                                   'action' => 'index'
                                                  ],
                                          ],

                                  ] 
                            ],
                        ],
                    ],  

                      'School Administration' => [
                        'link' => '#',
                        'class' => 'fa fa-sitemap',
                        'children' => [
                          'AcademicYears' => [
                              'link' => '#',
                              'children' => [
                                    'View Academic Years' => [
                                        'link' => [
                                              'controller' => 'AcademicYears',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Academic Year' => [
                                        'link' => [
                                                   'controller' => 'AcademicYears',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                          'Terms' => [
                              'link' => '#',
                              'children' => [
                                    'View Terms' => [
                                        'link' => [
                                              'controller' => 'Terms',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Term' => [
                                        'link' => [
                                                   'controller' => 'Terms',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Divisions' => [
                              'link' => '#',
                              'children' => [
                                    'View Divisions' => [
                                        'link' => [
                                              'controller' => 'Divisions',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Division' => [
                                        'link' => [
                                                   'controller' => 'Divisions',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Grades' => [
                              'link' => '#',
                              'children' => [
                                    'View Grades' => [
                                        'link' => [
                                              'controller' => 'Grades',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Grade' => [
                                        'link' => [
                                                   'controller' => 'Grades',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Contexts' => [
                              'link' => '#',
                              'children' => [
                                    'View Contexts' => [
                                        'link' => [
                                              'controller' => 'Contexts',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Context' => [
                                        'link' => [
                                                   'controller' => 'Contexts',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Division Grades' => [
                              'link' => '#',
                              'children' => [
                                    'View Division Grades' => [
                                        'link' => [
                                              'controller' => 'DivisionGrades',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Division Grade' => [
                                        'link' => [
                                                   'controller' => 'DivisionGrades',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Users' => [
                              'link' => '#',
                              'children' => [
                                    'View Users' => [
                                        'link' => [
                                              'controller' => 'Users',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add User' => [
                                        'link' => [
                                                   'controller' => 'Users',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Campuses' => [
                              'link' => '#',
                              'children' => [
                                    'View Campuses' => [
                                        'link' => [
                                              'controller' => 'Campuses',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Campus' => [
                                        'link' => [
                                                   'controller' => 'Campuses',
                                                   'action' => 'add'
                                                  ],
                                          ],
                                      'Campus Courses' => [
                                        'link' => [
                                                   'controller' => 'CampusCourses',
                                                   'action' => 'index'
                                                  ],
                                          ],
                                      'Campus Teachers' => [
                                        'link' => [
                                                   'controller' => 'CampusTeachers',
                                                   'action' => 'index'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Roles' => [
                              'link' => '#',
                              'children' => [
                                    'View Roles' => [
                                        'link' => [
                                              'controller' => 'Roles',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Role' => [
                                        'link' => [
                                                   'controller' => 'Roles',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Schools' => [
                              'link' => '#',
                              'children' => [
                                    'View Schools' => [
                                        'link' => [
                                              'controller' => 'Schools',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add School' => [
                                        'link' => [
                                                   'controller' => 'Schools',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Scales' => [
                              'link' => '#',
                              'children' => [
                                    'View Scales' => [
                                        'link' => [
                                              'controller' => 'Scales',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Scale' => [
                                        'link' => [
                                                   'controller' => 'Scales',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],
                            'Content Categories' => [
                              'link' => '#',
                              'children' => [
                                    'View Content' => [
                                        'link' => [
                                              'controller' => 'ContentCategories',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Content Category' => [
                                        'link' => [
                                                   'controller' => 'ContentCategories',
                                                   'action' => 'add'
                                                  ],
                                          ],
                                      'Content Values' => [
                                        'link' => [
                                                   'controller' => 'ContentValues',
                                                   'action' => 'index'
                                                  ],
                                          ],

                                  ] 
                            ],
                        ],
                      ],

                      'School Structure' => [
                        'link' => '#',
                        'class' => 'fa fa-sitemap',
                        'children' => [
                          'Sections' => [
                              'link' => '#',
                              'children' => [
                                    'View Sections' => [
                                        'link' => [
                                              'controller' => 'Sections',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Section' => [
                                        'link' => [
                                                   'controller' => 'Sections',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Courses' => [
                              'link' => '#',
                              'children' => [
                                    'View Courses' => [
                                        'link' => [
                                              'controller' => 'Courses',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Course' => [
                                        'link' => [
                                                   'controller' => 'Courses',
                                                   'action' => 'add'
                                                  ],
                                          ] 
                                  ] 
                            ],

                          ],
                        
                        ],
                        'Reports' => [
                        'link' => '#',
                        'class' => 'fa fa-sitemap',
                        'children' => [
                          'Reports Templates' => [
                              'link' => '#',
                              'children' => [
                                    'View Templates' => [
                                        'link' => [
                                              'controller' => 'ReportTemplates',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Templates' => [
                                        'link' => [
                                                   'controller' => 'ReportTemplates',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Reports Template Types' => [
                              'link' => '#',
                              'children' => [
                                    'View Templates Types' => [
                                        'link' => [
                                              'controller' => 'ReportTemplateTypes',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Templates Type' => [
                                        'link' => [
                                                   'controller' => 'ReportTemplateTypes',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Reports Pages' => [
                              'link' => '#',
                              'children' => [
                                    'View Pages' => [
                                        'link' => [
                                              'controller' => 'ReportPages',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Page' => [
                                        'link' => [
                                                   'controller' => 'ReportPages',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Reports Template Pages' => [
                              'link' => '#',
                              'children' => [
                                    'View Template Pages' => [
                                        'link' => [
                                              'controller' => 'ReportTemplatePages',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Template Page' => [
                                        'link' => [
                                                   'controller' => 'ReportTemplatePages',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Services' => [
                              'link' => '#',
                              'children' => [
                                    'View Types' => [
                                        'link' => [
                                              'controller' => 'SpecialServiceTypes',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Services' => [
                                        'link' => [
                                                   'controller' => 'SpecialServiceTypes',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ]


                          ],
                        
                        ],
                      ],

                    

                  'School' =>  [
                      'Go To Learning Board' => [
                        'link' => '/teachers#/',
                        'class' => 'fa-list-alt',
                      ],
                      'Reports' => [
                        'link' => '#',
                        'class' => 'fa fa-sitemap',
                        'children' => [
                          'Reports Templates' => [
                              'link' => '#', 
                              'children' => [
                                    'View Templates' => [
                                        'link' => [
                                              'controller' => 'ReportTemplates',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Templates' => [
                                        'link' => [
                                                   'controller' => 'ReportTemplates',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Reports Template Types' => [
                              'link' => '#',
                              'class' => 'fa fa-sitemap',
                              'children' => [
                                    'View Templates Types' => [
                                        'link' => [
                                              'controller' => 'ReportTemplateTypes',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Templates Type' => [
                                        'link' => [
                                                   'controller' => 'ReportTemplateTypes',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Reports Pages' => [
                              'link' => '#',
                              'children' => [
                                    'View Pages' => [
                                        'link' => [
                                              'controller' => 'ReportPages',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Page' => [
                                        'link' => [
                                                   'controller' => 'ReportPages',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ],
                            'Reports Template Pages' => [
                              'link' => '#',
                              'children' => [
                                    'View Template Pages' => [
                                        'link' => [
                                              'controller' => 'ReportTemplatePages',
                                              'action' => 'index'
                                            ],
                                          ],
                                      'Add Template Page' => [
                                        'link' => [
                                                   'controller' => 'ReportTemplatePages',
                                                   'action' => 'add'
                                                  ],
                                          ]
                                  ] 
                            ]

                          ],
                        
                        ],

                    ],

                    'Teacher' =>  [],
                    'Student' =>  [],
                    'Guardian' =>  [],

                ]
        ];
?>