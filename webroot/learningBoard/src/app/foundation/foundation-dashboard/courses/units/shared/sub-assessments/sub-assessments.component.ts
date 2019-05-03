import { Component, OnInit, Input, ViewChild, Output, ViewContainerRef, TemplateRef } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { BsModalService } from 'ngx-bootstrap/modal';
import { CoursesService } from '../../../../../../services/foundation/courses/courses.service';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';
import { UnitsService } from '../../../../../../services/foundation/units/units.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-sub-assessments',
  templateUrl: './sub-assessments.component.html',
  styleUrls: ['./sub-assessments.component.scss']
})
export class SubAssessmentsComponent implements OnInit {
  @Input() assessmentTypeId: any;
  @Input() assessmentSubtypeId: any;
  @Input() unitId: any;
  @Input() courseId: any;
  @Input() title: string;

  assessmentId = false;
  NewUnitTitle;
  NewUnitDateRange: any;
  isAccessible;
  saveTask;
  taskList: any = [];
  resources: any;
  description: any = null;
  courseDetails: any;
  selectedTask: any;
  modalBody: string;
  modalRef: BsModalRef;
  bsValue: Date = new Date();
  disableButton = false;
  standardTreeTitle: any;
  standardBlockTitle: any;
  impactTreeTitle: any;
  unitData: any;

  publishedHistory: any;
  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough',
      'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color',
      // 'inlineStyle', 'paragraphStyle',
      // 'paragraphFormat',
      'align',
      'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
      'insertLink', 'insertImage', 'insertVideo', 'embedly',
      // 'insertFile',
      'insertTable', '|',
      // 'emoticons', 
      'specialCharacters',
      // 'insertHR',
      'selectAll', 'clearFormatting', '|',
      // 'print', 
      'spellChecker',
      // 'help', 
      // 'html', '|', 
      'undo', 'redo'],
  }
  public disabled = false;
  public resourceType: any;
  public status: { isopen: boolean } = { isopen: false };

  public toggled(open: boolean): void {
    console.log('Dropdown is now: ', open);
  }


  constructor(
    private performanceService: PerformanceTaskService,
    private acivatedRoute: ActivatedRoute,
    private unitService: UnitsService,
    private courseService: CoursesService,
    private modalService: BsModalService,
    public toastr: ToastsManager,
    vcr: ViewContainerRef
  ) {
    this.toastr.setRootViewContainerRef(vcr);
  }

  ngOnInit() {
    this.standardTreeTitle = 'Academic Standards';
    this.impactTreeTitle = 'Impacts';
    this.standardBlockTitle = 'Standards';

    /* Fetch TaskList */
    if (this.assessmentSubtypeId && this.assessmentTypeId) {
      this.fetchTaskList(this.courseId, this.unitId);
      this.unitService.getUnit(this.courseId, this.unitId).subscribe((response) => {
        const data = response
        if (data != null && typeof data != 'undefined') {
          this.unitData = response;
        }
      }, (error) => console.warn('Error in getting course' + error)
      );
    }

    /* Course data */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        if (this.courseDetails != null && typeof this.courseDetails !== 'undefined') {
          this.courseDetails = this.courseDetails.data;
        }
      }, (error) => {
        console.warn(error);
      });
  }

  createTask() {
    this.performanceService.addNewTask(
      this.NewUnitTitle,
      (new Date(this.NewUnitDateRange[0])).toDateString(),
      (new Date(this.NewUnitDateRange[1])).toDateString(),
      this.unitId,
      this.description,
      this.courseId,
      this.assessmentId,
      this.assessmentTypeId,
      this.isAccessible,
      this.assessmentSubtypeId
    )
      .subscribe((response) => {
        let newTask: any;
        newTask = response;
        if (newTask != null && typeof newTask !== 'undefined') {
          if (this.assessmentId) {
            let index: any;
            index = this.taskList.map(function (x) { return x.id; }).indexOf(newTask.data.id);
            this.taskList.splice(index, 1);
            this.taskList.push(newTask.data);
          } else {
            this.assessmentId = newTask.data.id;
            this.taskList.push(newTask.data);
          }
        }

        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
        console.warn(error);
      });
  }
  onSelectionChange(selection) {
    this.isAccessible = Object.assign({}, this.isAccessible, selection);
  }

  deleteTask(task) {
    this.performanceService.deleteTask(
      this.courseId,
      this.unitId,
      task.id
    )
      .subscribe((response) => {
        let newTask: any;
        newTask = response;

        let index: any;
        index = this.taskList.map(function (x) { return x.id; }).indexOf(task.id);
        this.taskList.splice(index, 1);
        console.log(this.taskList);
      }, (error) => {
        console.warn(error);
      });
  }
  fetchTaskList(courseId, unitId) {
    this.performanceService.getTaskList(
      courseId, unitId, this.assessmentTypeId)
      .subscribe((response) => {
        let tasks: any = [];
        tasks = response;
        tasks = tasks.data;
        if (tasks.length > 0 && typeof tasks != 'undefined') {
          tasks.forEach(element => {
            if (element.assessment_subtype_id == this.assessmentSubtypeId) {
              this.taskList.push(element);
            }
            console.log('Tast list data' + this.taskList);
          });
        }
      }, (error) => {
        console.warn(error);
      });
  }

  newTaskModel(template: TemplateRef<any>) {
    this.selectedTask = null;
    this.assessmentId = false;
    this.NewUnitTitle = null;
    this.description = null;
    this.NewUnitDateRange = null;
    this.isAccessible = true;
    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
  }

  openModel(template: TemplateRef<any>, task) {
    this.selectedTask = task;
    this.assessmentId = task.id;
    this.NewUnitTitle = task.name;
    this.assessmentTypeId = task.assessment_type_id;
    this.description = task.description;
    this.isAccessible = task.is_accessible;
    this.getPublishHistory(task.id, 'assessment');
    // this.getcheckedGoals(task.id);
    if (task.start_date) {
      let startDate = new Date(task.start_date);
      let start_year = startDate.getFullYear();
      let start_month = startDate.getMonth();
      let start_day = startDate.getDate();
      let endDate = new Date(task.end_date);
      let end_year = endDate.getFullYear();
      let end_month = endDate.getMonth();
      let end_day = endDate.getDate();

      this.NewUnitDateRange = [new Date(start_year, start_month, start_day), new Date(end_year, end_month, end_day)];

    }

    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
  }
  getPublishHistory(objectId, objectType) {
    this.publishedHistory = null;
    this.courseService.getPublishHistory(objectId, objectType)
      .subscribe((res) => {
        this.publishedHistory = res;
        this.publishedHistory = this.publishedHistory.data;
      }, (error) => {
        console.warn(error);
      });
  }

  closeTaskModel(template: TemplateRef<any>) {
    this.modalRef.hide();
    this.selectedTask = null;
    this.assessmentId = null;
    this.NewUnitTitle = null;
    this.description = null;
    this.NewUnitDateRange = null;
    this.isAccessible = null;
    this.assessmentTypeId = null;
  }

  // Publishes the performance task to the events calendar
  publishTask(courseId, sectionId, selectedDateRangeValue) {
    var selectedDateRangeValue = selectedDateRangeValue.split("-");
    selectedDateRangeValue = selectedDateRangeValue.map(function (x) {
      return x.trim();
    })
    if (selectedDateRangeValue) {
      this.disableButton = true;
      this.unitService.publishSectionEvent(this.assessmentId, 'assessment', sectionId, selectedDateRangeValue[0], selectedDateRangeValue[1])
        .subscribe((res) => {
          this.toastr.success('Assessment published!', 'Success!');
          this.disableButton = false;
          this.getPublishHistory(this.assessmentId, 'assessment');
        }, (error) => {
          this.toastr.error('Unable to publish assessment. Error Message:' + error.message, 'Error!');
          this.disableButton = false;
        });
    }

  }
}



