import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { LocationStrategy, HashLocationStrategy } from '@angular/common';
import { CalendarModule } from 'angular-calendar';


import { AppComponent } from './app.component';
import { BsDropdownModule } from 'ngx-bootstrap/dropdown';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { NAV_DROPDOWN_DIRECTIVES } from './shared/nav-dropdown.directive';
import { FormsModule } from '@angular/forms';
import { ModalModule , AccordionModule } from 'ngx-bootstrap';
import { BsDatepickerModule } from 'ngx-bootstrap';
import { TreeModule } from 'angular-tree-component';
import { SelectModule } from 'ng2-select';
import { HttpClient, HttpHeaders, HttpClientModule } from '@angular/common/http';

// import { ChartsModule } from 'ng2-charts/ng2-charts';
import { SIDEBAR_TOGGLE_DIRECTIVES } from './shared/sidebar.directive';
import { AsideToggleDirective } from './shared/aside.directive';
import { BreadcrumbsComponent } from './shared/breadcrumb.component';
import { ToastModule } from 'ng2-toastr/ng2-toastr';
import { NouisliderModule } from 'ng2-nouislider';
import { FroalaEditorModule, FroalaViewModule } from 'angular-froala-wysiwyg';
// import { VerticalTimelineModule } from 'angular-vertical-timeline';

// Routing Module
import { AppRoutingModule } from './app.routing';

import { FullLayoutComponent } from './layouts/full-layout.component';
import { SimpleLayoutComponent } from './layouts/simple-layout.component';
import { GoalsComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/goals/goals.component';
import { UbdUnitOverviewComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/ubd-unit-overview.component';
import { AssessmentsComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/assessments/assessments.component';
import { LearningAcivitiesComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/learning-acivities/learning-acivities.component';
import { ResourcesReflectionsComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd-unit-overview/resources-reflections/resources-reflections.component';
import { FoundationDashboardComponent } from './foundation/foundation-dashboard/foundation-dashboard.component';
import { CoursesComponent } from './foundation/foundation-dashboard/courses/courses.component';
import { UbdComponent } from './foundation/foundation-dashboard/courses/units/ubd/ubd.component';
import { TeachingHubDashboardComponent } from './teaching-hub/teaching-hub-dashboard/teaching-hub-dashboard.component';
import { AnalyticsDashboardComponent } from './analytics/analytics-dashboard/analytics-dashboard.component';
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
import { ResourcesDirectiveComponent } from './foundation/foundation-dashboard/courses/units/shared/resources-directive/resources-directive.component';
import { ReflectionsDirectiveComponent } from './foundation/foundation-dashboard/courses/units/shared/reflections-directive/reflections-directive.component';
import { ImpactsComponent } from './foundation/foundation-dashboard/courses/units/shared/impacts/impacts.component';
import { StandardsComponent } from './foundation/foundation-dashboard/courses/units/shared/standards/standards.component';
import { UnitcontentComponent } from './foundation/foundation-dashboard/courses/units/shared/unitcontent/unitcontent.component';

import { CustomHttpsService } from './services/configuration/custom-https.service';
import { CoursesService } from './services/foundation/courses/courses.service';
import { UnitsService } from './services/foundation/units/units.service';
import { TeacherService } from './services/foundation/teachers/teacher.service';
import { UsersService } from './services/users/users.service';
import { AnalyticsService } from './services/analytics/analytics.service';
import { ResourcesReflectionsService } from './services/foundation/units/resources-reflections/resources-reflections.service';
import { PerformanceTaskService } from './services/foundation/units/performance-task/performance-task.service';
import { FeedbackService } from './services/feedback/feedback.service';
import { EvidencesService } from './services/evidences/evidences.service';
import { TeacherEvidencesService } from './services/teacher-evidences/teacher-evidences.service';

import { SelectAssessmentContentComponent } from './foundation/foundation-dashboard/courses/units/shared/select-assessment-content/select-assessment-content.component';
import { CalendarComponent } from './teaching-hub/teaching-hub-dashboard/calendar/calendar.component';
import { TreeUnitContentComponent } from './foundation/foundation-dashboard/courses/units/shared/tree-unit-content/tree-unit-content.component';
import { SanitizeHtmlPipe } from './foundation/foundation-dashboard/courses/units/shared/sanitize-html.pipe';
import { FilterPipe } from './pipes/filter/filter.pipe';
import { NgjstreeDirective } from './shared/js-tree-directive';

import { FullCalendarModule } from 'ng-fullcalendar';
import { TubdComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd.component';
import { TubdOverviewComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-overview.component';
import { TubdDesiredOutcomesComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-desired-outcomes/tubd-desired-outcomes.component';
import { TubdSummativeTaskComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-summative-task/tubd-summative-task.component';
import { TubdLearningExperiencesComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-learning-experiences/tubd-learning-experiences.component';
import { TubdResourceReflectionsComponent } from './foundation/foundation-dashboard/courses/units/tubd/tubd-overview/tubd-resource-reflections/tubd-resource-reflections.component';
import { DropdownUnitContentComponent } from './foundation/foundation-dashboard/courses/units/shared/dropdown-unit-content/dropdown-unit-content.component';
import { AddUnitSpecificComponentComponent } from './foundation/foundation-dashboard/courses/units/shared/add-unit-specific-component/add-unit-specific-component.component';
import { UnitStandardsComponent } from './foundation/foundation-dashboard/courses/units/shared/unit-standards/unit-standards.component';
import { UnitImpactsComponent } from './foundation/foundation-dashboard/courses/units/shared/unit-impacts/unit-impacts.component';
import { AnalyticsComponent } from './analytics/analytics.component';
import { CourseMapComponent } from './analytics/analytics-dashboard/course-map/course-map.component';
import { ChartModule, HIGHCHARTS_MODULES } from 'angular-highcharts';

import { CourseStrandDistributionComponent } from './analytics/shared/course-strand-distribution/course-strand-distribution.component';
import { StrandUsageComponent } from './analytics/shared/strand-usage/strand-usage.component';
import { StandardUsageTableComponent } from './analytics/shared/standard-usage-table/standard-usage-table.component';
import { SelectStudentComponent } from './analytics/shared/select-student/select-student.component';
import { ProgressReportComponent } from './analytics/progress-report/progress-report.component';
import { PolarChartComponent } from './analytics/shared/polar-chart/polar-chart.component';
import { StandardsPolarComponent } from './analytics/standards-polar/standards-polar.component';
import { AssessmentSpecificContentComponent } from './foundation/foundation-dashboard/courses/units/shared/assessment-specific-content/assessment-specific-content.component';

import { FeedbackDashboardComponent } from './feedback-dashboard/feedback-dashboard.component';
import { ReportsDashboardComponent } from './reports-dashboard/reports-dashboard.component';
import { ReportSectionComponent } from './report-section/report-section.component';
import { ReportsComponent } from './report-section/reports/reports.component';
import { SubAssessmentsComponent } from './foundation/foundation-dashboard/courses/units/shared/sub-assessments/sub-assessments.component';
import { UnitSummaryComponent } from './foundation/foundation-dashboard/courses/units/shared/unit-summary/unit-summary.component';
import { CommonContentUnitSummaryComponent } from './foundation/foundation-dashboard/courses/units/shared/common-content-unit-summary/common-content-unit-summary.component';
import { CoursesPolarComponent } from './analytics/standards-polar/courses-polar/courses-polar.component';
import { StudentDashboardComponent } from './student-dashboard/student-dashboard.component';
import { EvidenceComponent } from './student-dashboard/evidence/evidence.component';
import { EvidenceAddComponent } from './student-dashboard/evidence/evidence-add/evidence-add.component';
import { ImpactsAddTreeComponent } from './shared/impacts-add-tree/impacts-add-tree.component';
import { DeepFilterPipe } from './pipes/deep-filter/deep-filter.pipe';
import { EvidenceEditComponent } from './student-dashboard/evidence/evidence-edit/evidence-edit.component';
import { StudentEvidencesComponent } from './teaching-hub/student-evidences/student-evidences.component';
import { ParentDashboardComponent } from './parent-dashboard/parent-dashboard.component';
import { StudentReportComponent } from './parent-dashboard/student-report/student-report.component';
import { TeacherEvidencesComponent } from './teacher-evidences/teacher-evidences.component';
import { AddTeacherEvidencesComponent } from './teacher-evidences/add-teacher-evidences/add-teacher-evidences.component';
import { EditTeacherEvidencesComponent } from './teacher-evidences/edit-teacher-evidences/edit-teacher-evidences.component';
import { StudentCircumplexComponent } from './parent-dashboard/student-circumplex/student-circumplex.component';
import { StudentEvidenceComponent } from './parent-dashboard/student-evidence/student-evidence.component';
import { ArchivedUnitsComponentComponent } from './foundation/foundation-dashboard/courses/units/shared/archived-units-component/archived-units-component.component';
import { UnitSummaryAssessmentsComponent } from './foundation/foundation-dashboard/courses/units/shared/unit-summary-assessments/unit-summary-assessments.component';
import { UnitBrowserComponent } from './unit-browser/unit-browser.component';
import { UnitsComponent } from './unit-browser/units/units.component';
import { DigitalStrategiesComponent } from './foundation/foundation-dashboard/courses/units/shared/digital-strategies/digital-strategies.component';
import { FileExtensionPipe } from './pipes/file-extension-filter/file-extension.pipe';

@NgModule({
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    BsDropdownModule.forRoot(),
    TabsModule.forRoot(),
    BsDatepickerModule.forRoot(),
    FormsModule,
    TreeModule,
    ModalModule.forRoot(),
    // VerticalTimelineModule,
    AccordionModule.forRoot(),
    SelectModule,
    NouisliderModule,
    HttpClientModule,
    ToastModule.forRoot(),
    // ChartsModule,
    // ModalModule.forRoot()
    CalendarModule.forRoot(),
    FullCalendarModule,
    FroalaEditorModule.forRoot(),
    FroalaViewModule.forRoot(),
    ChartModule
  ],
  declarations: [
    AppComponent,
    FullLayoutComponent,
    SimpleLayoutComponent,
    NAV_DROPDOWN_DIRECTIVES,
    BreadcrumbsComponent,
    SIDEBAR_TOGGLE_DIRECTIVES,
    AsideToggleDirective,
    FoundationDashboardComponent,
    CoursesComponent,
    UbdComponent,
    TeachingHubDashboardComponent,
    AnalyticsDashboardComponent,
    GoalsComponent,
    UbdUnitOverviewComponent,
    AssessmentsComponent,
    LearningAcivitiesComponent,
    ResourcesReflectionsComponent,
    TransferComponent,
    PerformanceTaskComponent,
    ElementsOfSuccessComponent,
    LearningExperiencesComponent,
    TransferUnitOverviewComponent,
    TransferResourcesReflectionsComponent,
    PypComponent,
    PypUnitOverviewComponent,
    OurPurposeComponent,
    WeKnowComponent,
    WeLearnComponent,
    ResourcesComponent,
    ReflectionsComponent,
    FeedbackComponent,
    AssessmentComponent,
    UnitcontentComponent,
    ResourcesDirectiveComponent,
    ReflectionsDirectiveComponent,
    ImpactsComponent,
    StandardsComponent,
    SelectAssessmentContentComponent,
    CalendarComponent,
    TreeUnitContentComponent,
    SanitizeHtmlPipe,
    TubdComponent,
    TubdOverviewComponent,
    TubdDesiredOutcomesComponent,
    TubdSummativeTaskComponent,
    TubdLearningExperiencesComponent,
    TubdResourceReflectionsComponent,
    DropdownUnitContentComponent,
    AddUnitSpecificComponentComponent,
    UnitStandardsComponent,
    UnitImpactsComponent,
    AnalyticsComponent,
    CourseMapComponent,
    CourseStrandDistributionComponent,
    StrandUsageComponent,
    StandardUsageTableComponent,
    SelectStudentComponent,
    ProgressReportComponent,
    FilterPipe,
    NgjstreeDirective,
    PolarChartComponent,
    StandardsPolarComponent,
    AssessmentSpecificContentComponent,
    FeedbackDashboardComponent,
    ReportsDashboardComponent,
    ReportSectionComponent,
    ReportsComponent,
    SubAssessmentsComponent,
    UnitSummaryComponent,
    CommonContentUnitSummaryComponent,
    CoursesPolarComponent,
    StudentDashboardComponent,
    EvidenceComponent,
    EvidenceAddComponent,
    ImpactsAddTreeComponent,
    DeepFilterPipe,
    EvidenceEditComponent,
    StudentEvidencesComponent,
    ParentDashboardComponent,
    StudentReportComponent,
    TeacherEvidencesComponent,
    AddTeacherEvidencesComponent,
    EditTeacherEvidencesComponent,
    StudentCircumplexComponent,
    StudentEvidenceComponent,
    ArchivedUnitsComponentComponent,
    UnitSummaryAssessmentsComponent,
    UnitBrowserComponent,
    UnitsComponent,
    DigitalStrategiesComponent,
    FileExtensionPipe
  ],
  providers: [{
    provide: LocationStrategy,
    useClass: HashLocationStrategy,
  },
    CustomHttpsService,
    CoursesService,
    UsersService,
    UnitsService,
    TeacherService,
    ResourcesReflectionsService,
    PerformanceTaskService,
    FeedbackService,
    AnalyticsService,
    EvidencesService,
    TeacherEvidencesService
  ],
  bootstrap: [ AppComponent ],
  exports: [
    NgjstreeDirective
  ]
})
export class AppModule { }
