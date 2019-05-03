<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/', ['controller' => 'ReportSettings', 'action' => 'standardsAndImpacts', 'method' => 'POST']);
    // $routes->addExtensions(['pdf']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('api', function ($routes) {
        $routes->resources('Standards', [
            'map' => ['add' => ['action' => 'add', 'method' => 'POST'],
                      'edit' => ['action' => 'edit', 'method' => 'PUT'],
                      'get' => ['action' => 'get', 'method' => 'GET']
                     ]
            ]);
         $routes->resources('ReportTemplateStudentComments', [
            'map' => ['add' => ['action' => 'add', 'method' => 'POST'],
                      'edit/:id' => ['action' => 'edit', 'method' => 'PUT'],
                      'getStudentRecord/:id' => ['action' => 'getStudentRecord', 'method' => 'GET']
                     ]
            ]);
          $routes->resources('ReportTemplateStudentServices', [
            'map' => ['add' => ['action' => 'add', 'method' => 'POST'],
                      'getStudentServices/:id' => ['action' => 'getStudentServices', 'method' => 'GET'],
                      'removeStudentService/:id' => ['action' => 'removeStudentService', 'method' => 'POST']
                     ]
            ]);

           // $routes->resources('EvidenceContents', [
           //  'map' => [
           //            'getEvidenceContents/:id' => ['action' => 'getStudentServices', 'method' => 'GET'],
           //            'removeEvidenceContent' => ['action' => 'removeEvidenceContent', 'method' => 'POST']
           //           ]
           //  ]);
        // $routes->resources('Units', [
        //     'map' => ['unitStandards' => ['action' => 'unitStandards', 'method' => 'GET']
        //              ]
        //     ]);

    $routes->fallbacks('InflectedRoute');
});

Router::scope('/api', function ($routes) {
    $routes->resources('Courses', ['prefix' => 'api'], function ($routes) {
            $routes->resources('Units' , ['prefix' => 'api',
                'map' => ['copyOfUnit' => ['action' => 'copyOfUnit','method' => 'POST'],
                          'archivedUnits/:id' => ['action' => 'archivedUnits','method' => 'GET'],
                          'getUnits' => ['action' => 'getUnits','method' => 'GET'],
                          'deleteUnit/:id' => ['action' => 'deleteUnit','method' => 'DELETE']
                         ] ], function($routes){
                $routes->resources('UnitResources' , ['prefix' => 'api',
                                    'map' => ['uploadResources' => ['action' => 'uploadResources','method' => 'POST']]]);
                $routes->resources('UnitReflections' , ['prefix' => 'api']);
                $routes->resources('UnitStrands' , ['prefix' => 'api']);
                $routes->resources('Assessments' , ['prefix' => 'api'], function($routes){
                    $routes->resources('AssessmentResources' , ['prefix' => 'api']);
                    $routes->resources('AssessmentReflections' , ['prefix' => 'api']);
                    $routes->resources('AssessmentStrands' , ['prefix' => 'api']);
                    $routes->resources('AssessmentStandards' , ['prefix' => 'api']);
                    $routes->resources('AssessmentImpacts' , ['prefix' => 'api']);
                    $routes->resources('ContentCategories' , ['prefix' => 'api'], function($routes){
                        $routes->resources('AssessmentContents' , ['prefix' => 'api',
                                    'map' => ['addSpecificContent' => ['action' => 'addSpecificContent', 'method' => 'POST'],
                                              'indexSpecificContent' => ['action' => 'indexSpecificContent', 'method' => 'GET'],
                                              'editSpecificContent/:id' => ['action' => 'editSpecificContent', 'method' => 'PUT', ],
                                              'removeSpecificContent/:id' => ['action' => 'removeSpecificContent', 'method' => 'DELETE'],
                                              'removeAssessmentContent' => ['action' => 'removeAssessmentContent', 'method' => 'DELETE']
                                    ]
                                ]); 
                    });

                });
                $routes->resources('UnitStandards' , ['prefix' => 'api']);
                $routes->resources('UnitImpacts' , ['prefix' => 'api']);
                $routes->resources('ContentCategories' , ['prefix' => 'api'], function($routes){
                   $routes->resources('UnitContents' , ['prefix' => 'api',
                                    'map' => ['addContent' => ['action' => 'addContent', 'method' => 'POST'],
                                              'indexSpecificContent' => ['action' => 'indexSpecificContent', 'method' => 'GET'],
                                              'editContent/:id' => ['action' => 'editContent', 'method' => 'PUT', ],
                                              'removeContent/:id' => ['action' => 'removeContent', 'method' => 'DELETE']
                                    ]
                                ]); 
                });
       });
    });
    $routes->resources('Evaluations', ['prefix' => 'api','map' => [
                                                                    'evaluationSave' =>
                                                                            [
                                                                                'action' => 'evaluationSave',
                                                                                'method' => 'POST'
                                                                            ],
                                                                    'getStudents' =>
                                                                            [
                                                                                'action' => 'getStudents',
                                                                                'method' => 'GET'
                                                                            ],
                                                                   ]
                                                                ]);
});
/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
