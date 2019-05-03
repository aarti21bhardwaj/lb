import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// Layouts
import { FullLayoutComponent } from './layouts/full-layout.component';
import { SimpleLayoutComponent } from './layouts/simple-layout.component';
import { FoundationDashboardComponent } from './foundation/foundation-dashboard/foundation-dashboard.component';
import { CoursesComponent } from './foundation/foundation-dashboard/courses/courses.component';
import { UbdComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd.component';
import { GoalsComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/goals/goals.component';
import { UbdUnitOverviewComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/ubd-unit-overview.component';
import { AssessmentsComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/assessments/assessments.component';
import { LearningAcivitiesComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/learning-acivities/learning-acivities.component';
import { ResourcesReflectionsComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/resources-reflections/resources-reflections.component';
import { TeachingHubDashboardComponent } from './teaching-hub/teaching-hub-dashboard/teaching-hub-dashboard.component';
import { AnalyticsDashboardComponent } from './analytics/analytics-dashboard/analytics-dashboard.component';
import { ProgressReportComponent } from './analytics/progress-report/progress-report.component';
/* Transfer */
import { TransferComponent } from './foundation/foundation-dashboard/courses/units/transfer/transfer.component';
import { PerformanceTaskComponent } from './foundation/foundation-dashboard/courses/units/transfer/transfer-unit-overview/performance-task/performance-task.component';
import { ElementsOfSuccessComponent } from './foundation/foundation-dashboard/courses/units/transfer/transfer-unit-overview/elements-of-success/elements-of-success.component';
import { LearningExperiencesComponent } from './foundation/foundation-dashboard/courses/units/transfer/transfer-unit-overview/learning-experiences/learning-experiences.component';
import { TransferUnitOverviewComponent } from './foundation/foundation-dashboard/courses/units/transfer/transfer-unit-overview/transfer-unit-overview.component';
import { TransferResourcesReflectionsComponent } from './foundation/foundation-dashboard/courses/units/transfer/transfer-unit-overview/transfer-resources-reflections/transfer-resources-reflections.component';

import { PypComponent } from './foundation/foundation-dashboard/courses/units/pyp/pyp.component';
import { PypUnitOverviewComponent } from './foundation/foundation-dashboard/courses/units/pyp/pyp-unit-overview/pyp-unit-overview.component';
import { OurPurposeComponent } from './foundation/foundation-dashboard/courses/units/pyp/pyp-unit-overview/our-purpose/our-purpose.component';
import { WeKnowComponent } from './foundation/foundation-dashboard/courses/units/pyp/pyp-unit-overview/we-know/we-know.component';
import { WeLearnComponent } from './foundation/foundation-dashboard/courses/units/pyp/pyp-unit-overview/we-learn/we-learn.component';
import { ResourcesComponent } from './foundation/foundation-dashboard/courses/units/pyp/pyp-unit-overview/resources/resources.component';
import { ReflectionsComponent } from './foundation/foundation-dashboard/courses/units/pyp/pyp-unit-overview/reflections/reflections.component';
import { FeedbackComponent } from './feedback/feedback.component';
import { AssessmentComponent } from './feedback/assessment/assessment.component';
import { CalendarComponent } from 'app/teaching-hub/teaching-hub-dashboard/calendar/calendar.component';

import { TubdComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd.component';
import { TubdOverviewComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-overview.component';
import { TubdDesiredOutcomesComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-desired-outcomes/tubd-desired-outcomes.component';
import { TubdSummativeTaskComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-summative-task/tubd-summative-task.component';
import { TubdLearningExperiencesComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-learning-experiences/tubd-learning-experiences.component';
import { TubdResourceReflectionsComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-resource-reflections/tubd-resource-reflections.component';
import { AnalyticsComponent } from 'app/analytics/analytics.component';
import { StandardsPolarComponent } from 'app/analytics/standards-polar/standards-polar.component';
import { CourseMapComponent } from 'app/analytics/analytics-dashboard/course-map/course-map.component';
import { FeedbackDashboardComponent } from './feedback-dashboard/feedback-dashboard.component';
import { ReportsDashboardComponent } from './reports-dashboard/reports-dashboard.component';
import { ReportSectionComponent } from './report-section/report-section.component';
import { ReportsComponent } from './report-section/reports/reports.component';
import { CoursesPolarComponent } from 'app/analytics/standards-polar/courses-polar/courses-polar.component';
import { StudentDashboardComponent } from './student-dashboard/student-dashboard.component';
import { EvidenceComponent } from './student-dashboard/evidence/evidence.component';
import { EvidenceAddComponent } from './student-dashboard/evidence/evidence-add/evidence-add.component';
import { EvidenceEditComponent } from './student-dashboard/evidence/evidence-edit/evidence-edit.component';
import { StudentEvidencesComponent } from './teaching-hub/student-evidences/student-evidences.component';
import { ParentDashboardComponent } from './parent-dashboard/parent-dashboard.component';
import { TeacherEvidencesComponent } from './teacher-evidences/teacher-evidences.component';
import { AddTeacherEvidencesComponent } from './teacher-evidences/add-teacher-evidences/add-teacher-evidences.component';
import { EditTeacherEvidencesComponent } from './teacher-evidences/edit-teacher-evidences/edit-teacher-evidences.component';
import { StudentReportComponent } from './parent-dashboard/student-report/student-report.component';
import { StudentCircumplexComponent } from './parent-dashboard/student-circumplex/student-circumplex.component';
import { StudentEvidenceComponent } from './parent-dashboard/student-evidence/student-evidence.component';

import { ArchivedUnitsComponentComponent } from './foundation/foundation-dashboard/courses/units/shared/archived-units-component/archived-units-component.component';
import { UnitBrowserComponent } from './unit-browser/unit-browser.component';
import { UnitsComponent } from './unit-browser/units/units.component';

export const routes: Routes = [
  {
    path: '',
    redirectTo: 'teaching-hub',
    pathMatch: 'full'
  },
  {
    path: 'foundation',
    children: [
      {
        path: 'courses',
        component: FoundationDashboardComponent,
        children: [
          // { path: '', component: FoundationDashboardComponent, pathMatch: 'full' },
          { path: ':course_id', component: CoursesComponent, pathMatch: 'full' },
          { path: ':course_id/archived-units/:term_id', component: ArchivedUnitsComponentComponent, pathMatch: 'full' },
        ],
      },
      // {
      //   path: 'courses/:course_id/archived-units',
      //   component: ArchivedUnitsComponentComponent,
      //   // children: [
      //   //   { path: '', component: ArchivedUnitsComponentComponent, pathMatch: 'full' },
      //   //   // { path: ':course_id', component: CoursesComponent, pathMatch: 'full' },
      //   // ],
      // },
      {
        path: 'courses/:course_id/units/ubd/:unit_id',
        component: UbdComponent,
        children: [
        { path: '', component: UbdUnitOverviewComponent, pathMatch: 'full' },
        { path: 'goals', component: GoalsComponent, pathMatch: 'full'},
        { path: 'assessments', component: AssessmentsComponent, pathMatch: 'full'},
        { path: 'learning-activities', component: LearningAcivitiesComponent, pathMatch: 'full'},
        { path: 'resources-reflections', component: ResourcesReflectionsComponent, pathMatch: 'full'}
        ],
      },
      {
        path: 'courses/:course_id/units/tUbd/:unit_id',
        component: TubdComponent,
        children: [
          { path: '', component: TubdOverviewComponent, pathMatch: 'full' },
          { path: 'desired-outcomes', component: TubdDesiredOutcomesComponent, pathMatch: 'full' },
          { path: 'learning-experiences', component: TubdLearningExperiencesComponent, pathMatch: 'full' },
          { path: 'summative-task', component: TubdSummativeTaskComponent, pathMatch: 'full' },
          { path: 'resources-reflections', component: TubdResourceReflectionsComponent, pathMatch: 'full' }
        ],
      },
      {
        path: 'courses/:course_id/units/transfer/:unit_id',
        component: TransferComponent,
        children: [
          { path: '', component: TransferUnitOverviewComponent, pathMatch: 'full' },
          { path: 'success-elements', component: ElementsOfSuccessComponent, pathMatch: 'full' },
          { path: 'learning-experiences', component: LearningExperiencesComponent, pathMatch: 'full' },
          { path: 'performance-task', component: PerformanceTaskComponent, pathMatch: 'full' },
          { path: 'transfer-resources-reflections', component: TransferResourcesReflectionsComponent, pathMatch: 'full' }
        ],
      },
      { path: 'courses/:course_id/units/pyp/:unit_id',
        component: PypComponent,
        children: [
        { path: '', component: PypUnitOverviewComponent, pathMatch: 'full' },
        { path: 'purpose', component: OurPurposeComponent, pathMatch: 'full'},
        { path: 'know', component: WeKnowComponent, pathMatch: 'full'},
        { path: 'learn', component: WeLearnComponent, pathMatch: 'full'},
        { path: 'resources', component: ResourcesComponent, pathMatch: 'full'},
        { path: 'reflections', component: ReflectionsComponent, pathMatch: 'full'}
        ],
      }
    ]
  },
  {
    path: 'analytics',
    children: [
      { path: '', component: AnalyticsComponent, pathMatch: 'full'},
      {
        path: 'course-map',
        component: AnalyticsDashboardComponent,
        children: [
          { path: ':course_id', component: CourseMapComponent, pathMatch: 'full' },
        ],
      },
      {
        path: 'progress-report',
        children: [
          { path: '', component: ProgressReportComponent, pathMatch: 'full' },
          { path: ':section_id/:student_id', component: ProgressReportComponent, pathMatch: 'full' },
        ],
      },
      {
        path: 'circumplex',
        children: [
          { path: '', component: StandardsPolarComponent, pathMatch: 'full' },
          {
            path: ':term_id/:section_id/:student_id', 
            component: StandardsPolarComponent,
            pathMatch: 'full',
          },
          { path: ':term_id/:section_id/:student_id/:course_id', component: CoursesPolarComponent, pathMatch: 'full'}
        ],
      }
    ]
  },
  {
    path: 'teaching-hub',
    component: TeachingHubDashboardComponent,
    children: [
      {
        path: ':section_id/:course_id',
        component: CalendarComponent,
        pathMatch: 'full'
      },
    ]
  },
  {
    path: 'teacher-evidences',
    component: TeacherEvidencesComponent,
    data: { componentName: 'teacher-evidences' },
    children: [
      {
        path: 'add',
        component: AddTeacherEvidencesComponent,
        data: { componentName: 'teacher-evidence-add' }
      },
      {
        path: 'edit/:evidence_id',
        component: EditTeacherEvidencesComponent,
        data: { componentName: 'teacher-evidence-edit' }
      }
    ]
  },
  {
    path: 'student-evidences/:student_id',
    component: StudentEvidencesComponent,
    data: { componentName: 'student-evidence' },
    pathMatch: 'full'
  },
  {
    path: '',
    component: FullLayoutComponent,
    data: {
      title: 'Home'
    },
    children: [
      {
        path: 'dashboard',
        loadChildren: './dashboard/dashboard.module#DashboardModule'
      },
      {
        path: 'components',
        loadChildren: './components/components.module#ComponentsModule'
      },
      {
        path: 'icons',
        loadChildren: './icons/icons.module#IconsModule'
      },
      {
        path: 'widgets',
        loadChildren: './widgets/widgets.module#WidgetsModule'
      },
      {
        path: 'charts',
        loadChildren: './chartjs/chartjs.module#ChartJSModule'
      }
    ]
  },
  {
    path: 'pages',
    component: SimpleLayoutComponent,
    data: {
      title: 'Pages'
    },
    children: [
      {
        path: '',
        loadChildren: './pages/pages.module#PagesModule',
      }
    ]
  },
  {
    path: 'feedback/:section_id/assessment/:assessment_id',
    component: FeedbackComponent,
    children: [
      { path: ':student_id', component: AssessmentComponent, pathMatch: 'full' },
    ]
  },
  {
    path: 'feedback',
    children: [
      { path: '', 
        component: FeedbackDashboardComponent, 
        pathMatch: 'full' 
      },
      { path: ':section_id', 
        component: FeedbackDashboardComponent,
        pathMatch: 'full'
       },
    ]
  },
  {
    path: 'reports',
    component: ReportsDashboardComponent,
  },
  {
    path: 'reports/section/:section_id',
    component: ReportSectionComponent,
    data: {
            componentName : 'report-section',
          },
    children: [
      {
        path: ':student_id',
        component: ReportsComponent,
        data: { componentName: 'reports' },
        pathMatch: 'full'
      },
    ]
  },
  {
    path: 'student-dashboard',
    component: StudentDashboardComponent,
    data: { componentName: 'student-dashboard' },
  },
  {
    path: 'evidences',
    component: EvidenceComponent,
    data: { componentName: 'evidences' },
    children: [
      {
        path: 'add',
        component: EvidenceAddComponent,
        data: { componentName: 'evidence-add' }
      },
      {
        path: 'edit/:evidence_id',
        component: EvidenceEditComponent,
        data: { componentName: 'evidence-edit' }
      }
    ]
  },
  {
    path: 'parent-dashboard',
    component: ParentDashboardComponent,
    data: { componentName: 'parent-dashboard' },
  },
  {
    path: 'student-report/:student_id/:section_id/:term_id/:course_id',
    component: StudentReportComponent,
    data: { componentName: 'student-report' },
  },
  {
    path: 'student-circumplex/:student_id/:section_id/:term_id/:course_id',
    component: StudentCircumplexComponent,
    data: { componentName: 'student-circumplex' },
  },
  {
    path: 'student-evidence/:student_id/:section_id/:term_id/:course_id',
    component: StudentEvidenceComponent,
    data: { componentName: 'student-evidence' },
  },

  {
    path: 'unit-browser',
    component: UnitBrowserComponent,
    data: {
      componentName: 'unit-browser',
    },
    children: [
      {
        path: ':course_id',
        component: UnitsComponent,
        data: { componentName: 'units' },
        pathMatch: 'full'
      },
    ]
  },
  
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
