import {
  Component,
  AfterViewInit,
  OnInit,
  EventEmitter,
  OnDestroy,
  Input,
  ViewChild,
  TemplateRef,
  Output,
  ViewContainerRef
} from '@angular/core';
import 'tinymce';
import 'tinymce/themes/modern';
import 'tinymce/plugins/table';
import 'tinymce/plugins/link';
import { ActivatedRoute } from '@angular/router';
import { ElementRef } from '@angular/core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { CoursesService } from './../../../../../../../services/foundation/courses/courses.service';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../../services/foundation/units/performance-task/performance-task.service';
import { TransferComponent } from '../../../transfer/transfer.component';
import { UnitsService } from './../../../../../../../services/foundation/units/units.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
declare var tinymce: any;


@Component({
  selector: 'app-learning-experiences',
  templateUrl: './learning-experiences.component.html',
  styleUrls: ['./learning-experiences.component.scss']
})
export class LearningExperiencesComponent implements OnInit {
  getStrategyData: any;
  strategyValueId: any;
  selectedStrategy: any;
  commonStrategyId: any;
  commonStrategies: any;
  spinnerEnabled: boolean = false;
  unitId;
  courseId;
  assessmentId;
  NewUnitTitle;
  NewUnitDateRange:any;
  isAccessible;
  saveTask;
  taskList:any;
  resources: any;
  description:any=null;
  courseDetails: any;
  selectedTask:any;
  modalBody:string;
  bsValue: Date = new Date();
  editor;
  modalRef: BsModalRef;
  disableButton: boolean = false;
  standardTreeTitle: any;
  standardBlockTitle: any;
  impactTreeTitle:any;
  @Input() elementId: String;
  mode = 'highlight';
  unitData:any;
  showHistoryTab = false;
  startDateAndEndDate = new Date();
  @Output() onEditorContentChange = new EventEmitter();
  publishedHistory:any;
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
      'undo', 'redo']
  }

  constructor(
    private performanceService: PerformanceTaskService,
    private acivatedRoute: ActivatedRoute,
    private element: ElementRef,
    private modalService: BsModalService,
    private parent: TransferComponent,
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
      console.log(this.unitId);
      console.log(this.courseId);
      this.spinnerEnabled = true;
      this.fetchTaskList(res.course_id,res.unit_id);
      this.unitService.getUnit(this.courseId,this.unitId).subscribe((res) => {
        this.unitData = res;
        this.unitData = this.unitData;
        this.spinnerEnabled = false;
      }, (error) => console.warn('Error in getting course' + error)
      );
    });
    parent.isMetaUnitActive = false;
  }

  createTask(){
    this.performanceService.addNewTask(
                              this.NewUnitTitle,
                              this.NewUnitDateRange? (new Date(this.NewUnitDateRange[0])).toDateString():'',
                              this.NewUnitDateRange? (new Date(this.NewUnitDateRange[1])).toDateString():'',                              this.unitId,
                              this.description,
                              this.courseId,
                              this.assessmentId,
                              4,
                              this.isAccessible  
                            )
      .subscribe((response) => {
        this.courseService.refreshUnitSummary();
        this.toastr.success('Learning Experience Saved!', 'Success!');
        let newTask:any;
        newTask = response;
        if(this.assessmentId){
          let index:any;
          index=this.taskList.map(function(x){ return x.id; }).indexOf(newTask.data.id);
          this.taskList.splice(index,1);
          this.taskList.push(newTask.data);
        }else{
          this.assessmentId = newTask.data.id; 
          this.taskList.push(newTask.data);
        }
     
        console.log(this.taskList);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
        this.toastr.error('Learning Experience could not be saved', 'Error!');
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
  deleteTask(){
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
          index = this.taskList.map(function(x){ return x.id; }).indexOf(task.id);
          this.taskList.splice(index,1);
          console.log(this.taskList);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
          console.warn(error);
      });
  }
  fetchTaskList(courseId,unitId){
    this.spinnerEnabled = true;
    this.performanceService.getTaskList(
                courseId,unitId,4)
      .subscribe((response) => {
        this.taskList = response;
        this.taskList = this.taskList.data;
        console.log(this.taskList);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
        this.spinnerEnabled = false;
      }, (error) => {
        console.warn(error);
      });
}

newTaskModel(template: TemplateRef<any>){
  this.selectedTask = null;
  this.assessmentId = null;
  this.NewUnitTitle = null;
  this.description = null;
  this.NewUnitDateRange = null;
  this.isAccessible = 1;
  this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
}

openModel(template: TemplateRef<any>,task){
  this.startDateAndEndDate= new Date();
  this.selectedTask = task;
  this.assessmentId = task.id;
  this.NewUnitTitle = task.name;
  this.description = task.description;
  this.isAccessible = task.is_accessible;
  this.showHistoryTab = task.isPublished;
  this.getPublishHistory(task.id,'assessment');
  this.getSelectedStrategy();
  if(task.start_date){
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
getPublishHistory(objectId, objectType){
  this.publishedHistory =null;
  this.courseService.getPublishHistory(objectId,objectType)
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
  this.showHistoryTab = false;
  this.selectedStrategy = [];
}


  ngOnInit() {
    this.standardTreeTitle = 'Academic Standards';
    this.impactTreeTitle='Impacts';
    this.standardBlockTitle = 'What are the learning goals to be addressed?';
    /* Course data */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'common_strategy') {
            this.commonStrategies = element.content_values;
            this.commonStrategyId = element.id
          }
        });
      }, (error) => {
        console.warn(error);
      });
  }

  ngAfterViewInit() {
    tinymce.init({
      selector: 'textarea',
      menubar: false,
      statusbar: false,
      plugins: ['link', 'table'],
     skin_url: 'learningBoard/dist/assets/skins/lightgray',
      setup: editor => {
        this.editor = editor;
        editor.on('keyup change', () => {
          const content = editor.getContent();
          this.onEditorContentChange.emit(content);
        });
      }
    });
  }
  ngOnDestroy() {
    tinymce.remove(this.editor);
  }

  public disabled = false;
  public status: { isopen: boolean } = { isopen: false };

  public toggled(open: boolean): void {
    console.log('Dropdown is now: ', open);
  }

  public resourceType: any;
  
  addNewLearningExperience() {

  }

  // Publishes the performance task to the events calendar
  publishTask(courseId, sectionId, selectedDateRangeValue){
    if(selectedDateRangeValue){
      this.disableButton= true;
      this.unitService.publishSectionEvent(this.assessmentId,'assessment',sectionId,selectedDateRangeValue[0],selectedDateRangeValue[1]).subscribe((res) => {
        this.startDateAndEndDate= new Date();
        this.toastr.success('Assessment published!', 'Success!');
        this.disableButton= false;
        this.getPublishHistory(this.assessmentId,'assessment');
      }, (error) => {
        this.toastr.error('Unable to publish assessment. Error Message:'+ error.message, 'Error!');
        this.disableButton= false;
      });
    }
  
  }

  getSelectedStrategy() {
    this.unitService.getAssessmentcontents(
      this.courseId, this.unitId, this.commonStrategyId, this.assessmentId)
      .subscribe((res) => {
        let getData: any;
        getData = res;
        getData = getData.response;
        // getData = getData.data.assessment_contents;
        this.getStrategyData = getData.data.assessment_contents;
        this.selectedStrategy = [];
        this.getStrategyData.forEach(element => {
          this.selectedStrategy.push({ id: element.id, text: element.content_value.text });
        });
      }, (error) => {
        console.warn(error);
      });
  }

  public selected(value: any): void {
    console.log('Selected value is: ', value);
  }
  public removed(value: any): void {
    console.log('Removed value is: ', value);
  }
  public refreshValue(value: any): void {
    console.log('This is the selected value' + value);
    this.strategyValueId = value.id;
  }


  addAssessmentStrategy() {
    if (this.selectedStrategy && typeof this.selectedStrategy !== 'undefined' && this.selectedStrategy.length > 0) {
      this.unitService.deleteAssessmentContents(this.courseId, this.unitId, this.commonStrategyId, 
        this.getStrategyData[0].content_value_id, this.assessmentId, 'commonContent')
        .subscribe((res) => {
          console.log(res);
          this.unitService.addAssessmentContents(this.courseId, this.unitId, this.commonStrategyId,
             this.strategyValueId, this.assessmentId, 'commonContent')
            .subscribe((response) => {
              console.log(response);
              this.getSelectedStrategy();
              this.toastr.success('Saved!', 'Success!');
            }, (error) => {
              this.toastr.error(error, 'Error!');
              console.warn(error);
            });
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });

    } else {
      this.unitService.addAssessmentContents(this.courseId, this.unitId, this.commonStrategyId, this.strategyValueId,
         this.assessmentId, 'commonContent')
        .subscribe((res) => {
          console.log(res);
          this.getSelectedStrategy();
          this.toastr.success('Saved!', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }

  dateChange(event){
    this.publishButton = true;
  }
}
