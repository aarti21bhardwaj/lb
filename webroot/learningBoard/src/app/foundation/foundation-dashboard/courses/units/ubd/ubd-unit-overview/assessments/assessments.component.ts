import { Component, OnInit, Input, ViewChild, Output, ViewContainerRef, TemplateRef } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ElementRef } from '@angular/core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { CoursesService } from './../../../../../../../services/foundation/courses/courses.service';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../../services/foundation/units/performance-task/performance-task.service';
import { UbdComponent } from '../../../ubd/ubd.component';
import { UnitsService } from './../../../../../../../services/foundation/units/units.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { LabelSettings } from '../../../../../../../label-settings';


@Component({
  selector: 'app-assessments',
  templateUrl: './assessments.component.html',
  styleUrls: ['./assessments.component.scss']
})
export class AssessmentsComponent implements OnInit {
  enduringUnderstandingsId: any;
  understandings: any;
  knowledge: any;
  knowledgeId: any;
  skillId: any;
  essentialQuestionsId: any;
  skills: any;
  questions: any;
  spinnerEnabled = false;
  unitId;
  courseId;
  assessmentId;
  NewUnitTitle;
  NewUnitDateRange: any=[];
  isAccessible;
  saveTask;
  taskList: any = [];
  resources: any;
  description: any = null;
  courseDetails: any;
  selectedTask: any;
  modalBody: string;
  bsValue: Date = new Date();
  modalRef: BsModalRef;
  disableButton = false;
  standardTreeTitle: any;
  standardBlockTitle: any;
  impactTreeTitle: any;
  mode = 'highlight';
  unitData: any;
  assessmentTypeId: any;
  evalCriteriaId: any;
  // evalCriteria: any;
  commentId: any;
  commonTransferGoals: any;
  goalId: any;
  unitCheckedData: any= [];
  showHistoryTab = false;
  // comment: any;
  startDateAndEndDate= new Date();
  publishedHistory: any;
  publishButton = false;
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
    private element: ElementRef,
    private modalService: BsModalService,
    private parent: UbdComponent,
    private unitService: UnitsService,
    private courseService: CoursesService,
    public toastr: ToastsManager,
    vcr: ViewContainerRef
  ) {
    this.toastr.setRootViewContainerRef(vcr);
    this.acivatedRoute.parent.params.subscribe(res => {
      console.log(res);
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
      console.log('This is the unit id in assessments ' + this.unitId);
      console.log('This is the course id in assessments ' + this.courseId);
      this.spinnerEnabled = true;
      if (this.courseId && this.unitId) {
        this.fetchTaskList(res.course_id, res.unit_id);
        this.unitService.getUnit(this.courseId, this.unitId).subscribe((response) => {
          const data = response
          if (data != null && typeof data != 'undefined') {
            this.unitData = response;
          }
          this.spinnerEnabled = false;
        }, (error) => console.warn('Error in getting course' + error)
        );
      }
    });
    parent.isMetaUnitActive = false;
  }

  createTask() {
    this.performanceService.addNewTask(
      this.NewUnitTitle,
      this.NewUnitDateRange? (new Date(this.NewUnitDateRange[0])).toDateString():'',
      this.NewUnitDateRange? (new Date(this.NewUnitDateRange[1])).toDateString():'',
      this.unitId,
      this.description,
      this.courseId,
      this.assessmentId,
      this.assessmentTypeId,
      this.isAccessible
    )
      .subscribe((response) => {
        this.courseService.refreshUnitSummary();
        this.toastr.success('Task Saved!', 'Success!');
        let newTask: any;
        newTask = response;
        if (newTask != null && typeof newTask !== 'undefined') {
          console.log('assessment added response' + response);
          this.fetchTaskList(this.courseId, this.unitId);
          this.assessmentId = newTask.data.id;
          // if (this.assessmentId) {
          //   console.log('In update assessment');
          //   let index: any;
          //   index = this.taskList.map(function (x) { return x.id; }).indexOf(newTask.data.id);
          //   console.log('index in adding task' + index);
          //   this.taskList.splice(index, 1);
          //   this.taskList.push(newTask.data);
          //   console.log('task is updated and pushed in the task list' + this.taskList);
          // } else {
          //   console.log(' increating new task');
          //   this.assessmentId = newTask.data.id;
          //   console.log('before pushing print tasklist array' + this.taskList);
          //   this.taskList.push(newTask.data);
          //   console.log('new task is added in else case in the task list' + this.taskList);
          // }
          console.log(this.taskList);
        }

        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
        this.toastr.error('Task could not be saved', 'Error!');
        console.warn(error);
      });
  }
  onSelectionChange(selection) {
    this.isAccessible = Object.assign({}, this.isAccessible, selection);
  }
  deleteModel(template: TemplateRef<any>,task){
    this.selectedTask = task;
    this.modalRef = this.modalService.show(template, { class: 'modal-sm' });
  }
  deleteTask() {
    let task=this.selectedTask;
    this.performanceService.deleteTask(
      this.courseId,
      this.unitId,
      task.id
    )
      .subscribe((response) => {
        this.courseService.refreshUnitSummary();
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
    this.spinnerEnabled = true;
    this.performanceService.getTaskList(
      courseId, unitId, this.assessmentTypeId)
      .subscribe((response) => {
        let tasks: any = [];
        tasks = response;
        tasks = tasks.data;
        if (tasks.length > 0 && typeof tasks != 'undefined') {
          tasks.forEach(element => {
            if (element.assessment_type_id == 1 || element.assessment_type_id == 2) {
              this.taskList.push(element);
            }
          });
        }
        // this.taskList = response;
        // console.log('fetching tasklist response' + this.taskList);
        // this.taskList = this.taskList.data;
        // console.log(this.taskList);
        this.spinnerEnabled = false;
      }, (error) => {
        console.warn(error);
      });
  }

  newTaskModel(template: TemplateRef<any>) {
    this.selectedTask = null;
    this.assessmentId = null;
    this.NewUnitTitle = null;
    this.description = null;
    this.NewUnitDateRange = null;
    this.isAccessible = 1;
    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
  }

  openModel(template: TemplateRef<any>, task) {
    this.startDateAndEndDate= new Date();
    this.selectedTask = task;
    this.assessmentId = task.id;
    this.NewUnitTitle = task.name;
    this.assessmentTypeId = task.assessment_type_id;
    console.log('getting assessment type id when open modal' + this.assessmentTypeId);
    this.description = task.description;
    this.isAccessible = task.is_accessible;
    this.showHistoryTab = task.isPublished;
    this.getPublishHistory(task.id, 'assessment');
    this.getcheckedGoals(task.id);
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
    this.isAccessible = 1;
    this.assessmentTypeId = null;
    this.showHistoryTab = false;
  }


  ngOnInit() {
    this.standardTreeTitle = 'Academic Standards';
    this.impactTreeTitle = LabelSettings.IMPACTS;
    this.standardBlockTitle = 'Standards';
    /* Course data */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        console.log('In assessment Init getting course detail  data' + response);
        if (this.courseDetails != null && typeof this.courseDetails !== 'undefined') {
          this.courseDetails = this.courseDetails.data;
          this.courseDetails.content_categories.forEach(element => {
            if (element.type === 'evaluation_criteria') {
              console.log('In if when both types are equal');
              const data: any = element;
              if (data != null && typeof data !== 'undefined') {
                this.evalCriteriaId = element.id;
              }
            }
            if (element.type === 'comments') {
              console.log('In if when both types are equal');
              const data: any = element;
              if (data != null && typeof data !== 'undefined') {
                this.commentId = element.id;
              }
            }
            if (element.type === 'common_transfer_goals') {
              console.log('In if when both types are equal');
              this.commonTransferGoals = element;
              this.goalId = this.commonTransferGoals.id;
              this.unitService.getUnitContent(
                this.courseId, this.unitId, this.goalId)
                .subscribe((res) => {
                  this.commonTransferGoals = res;
                  this.commonTransferGoals = this.commonTransferGoals.response.data;
                }, (error) => {
                  console.warn(error);
                });
              console.log('these are the common understandings' + this.commonTransferGoals);
            }
            if (element.type === 'enduring_understandings') {
              console.log('In if when both types are equal');
              // this.understandings = element;
              this.enduringUnderstandingsId = element.id;
              console.log('these are the enduring understandings' + this.understandings);
              this.unitService.getUnitContent(
                this.courseId, this.unitId, this.enduringUnderstandingsId)
                .subscribe((res) => {
                  this.understandings = res;
                  this.understandings = this.understandings.response.data;
                }, (error) => {
                  console.warn(error);
                });
            }
            if (element.type === 'knowledge') {
              console.log('In if when both types are equal');
              // this.knowledge = element;
              this.knowledgeId = element.id;
              this.unitService.getUnitContent(
                this.courseId, this.unitId, this.knowledgeId)
                .subscribe((res) => {
                  this.knowledge = res;
                  this.knowledge = this.knowledge.response.data;
                }, (error) => {
                  console.warn(error);
                });
              console.log('these are the common knowledge' + this.knowledge);
            }
            if (element.type === 'skills') {
              console.log('In if when both types are equal');
              // this.skills = element;
              this.skillId = element.id;
              this.unitService.getUnitContent(
                this.courseId, this.unitId, this.skillId)
                .subscribe((res) => {
                  this.skills = res;
                  this.skills = this.skills.response.data;
                }, (error) => {
                  console.warn(error);
                });
              console.log('these are the common knowledge' + this.skills);
            }
            if (element.type === 'essential_questions') {
              console.log('In if when both types are equal');
              // this.questions = element;
              this.essentialQuestionsId = element.id;
              this.unitService.getUnitContent(
                this.courseId, this.unitId, this.essentialQuestionsId)
                .subscribe((res) => {
                  this.questions = res;
                  this.questions = this.questions.response.data;
                }, (error) => {
                  console.warn(error);
                });
              console.log('these are the essential questions' + this.questions);
            }
          })
        }
      }, (error) => {
        console.warn(error);
      });
  }

  getObject(obj){
    return JSON.stringify(obj);
  }

  // Publishes the performance task to the events calendar
  publishTask(courseId, sectionId, selectedDateRangeValue) {
    
    if (selectedDateRangeValue) {
      this.disableButton = true;
      this.unitService.publishSectionEvent(this.assessmentId, 'assessment', sectionId, selectedDateRangeValue[0], selectedDateRangeValue[1])
      .subscribe((res) => {
        this.startDateAndEndDate= new Date();
        this.toastr.success('Assessment published!', 'Success!');
        this.disableButton = false;
        this.getPublishHistory(this.assessmentId, 'assessment');
      }, (error) => {
        this.toastr.error('Unable to publish assessment. Error Message:' + error.message, 'Error!');
        this.disableButton = false;
      });
    }

  }

  checked(e, id) {
    console.log('any event happend' + e.target);
    if (e.target.checked) {
      console.log('when checked box is checked' + id);
      this.unitService.addAssessmentContents(this.courseId, this.unitId, this.goalId, id, this.assessmentId, 'commonContent')
      .subscribe((res) => {
        console.log(res);
        this.toastr.success('Saved!', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
    if (!e.target.checked) {
      console.log('when checked box is unchecked' + id);
      this.unitService.deleteAssessmentContents(this.courseId, this.unitId, this.goalId, id, this.assessmentId, 'commonContent')
      .subscribe((res) => {
        console.log(res);
        this.toastr.success('Deleted Successfully', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
  }

  checkSelectedNode(node) {
    console.log(this.unitCheckedData);
    console.log('here is node info');
    console.log(node);
    console.log('node info ends');
    for (let x in this.unitCheckedData) {
      if (this.unitCheckedData[x].content_value_id == node.id) {
        return true;
      }
    }
  }

  getcheckedGoals(assessmentId) {
    this.unitService.getAssessmentcontents(
      this.courseId, this.unitId, this.goalId, assessmentId)
      .subscribe((res) => {
        this.unitCheckedData = res;
        this.unitCheckedData = this.unitCheckedData.response.data.assessment_contents;
      }, (error) => {
        console.warn(error);
      });
  }

  dateChange(event){
     this.publishButton = true;
   }
}
