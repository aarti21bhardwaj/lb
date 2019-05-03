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
import { TubdComponent } from '../../../tubd/tubd.component';
import { UnitsService } from './../../../../../../../services/foundation/units/units.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { LabelSettings } from '../../../../../../../label-settings';
import { AppSettings } from '../../../../../../../app-settings';
declare var tinymce: any;
declare var $: any;

@Component({
  selector: 'app-tubd-learning-experiences',
  templateUrl: './tubd-learning-experiences.component.html',
  styleUrls: ['./tubd-learning-experiences.component.scss']
})
export class TubdLearningExperiencesComponent implements OnInit {

  spinnerEnabled: boolean = false;
  unitId;
  courseId;
  assessmentId;
  NewUnitTitle;
  NewUnitDateRange:any=[];
  isAccessible:boolean=false;
  saveTask;
  taskList:any;
  resources: any;
  reflections: any;
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
  @Output() onEditorContentChange = new EventEmitter();
  publishedHistory:any;
  mode = 'highlight'; //or mode = 'hide'
  unitData:any;
  showHistoryTab = false;
  publishButton = false;
  startDateAndEndDate= new Date();
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
      'undo', 'redo',
      'insert'
    ]
  }

  constructor(
    private performanceService: PerformanceTaskService,
    private acivatedRoute: ActivatedRoute,
    private element: ElementRef,
    private modalService: BsModalService,
    private parent: TubdComponent,
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
    console.log('is accessible');
    console.log(this.isAccessible);
    // this.isAccessible=value;
    this.performanceService.addNewTask(
                              this.NewUnitTitle,
                              this.NewUnitDateRange? (new Date(this.NewUnitDateRange[0])).toDateString():'',
                              this.NewUnitDateRange? (new Date(this.NewUnitDateRange[1])).toDateString():'',
                              this.unitId,
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
        this.fetchTaskList(this.courseId, this.unitId);
        this.assessmentId = newTask.data.id;
        // if(this.assessmentId){
        //   let index:any;
        //   index=this.taskList.map(function(x){ return x.id; }).indexOf(newTask.data.id);
        //   this.taskList.splice(index,1);
        //   this.taskList.push(newTask.data);
        // }else{
        //   this.assessmentId = newTask.data.id; 
        //   this.taskList.push(newTask.data);
        // }
     
        console.log(this.taskList);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
        this.toastr.error('Learning Experience not be saved', 'Error!');
        console.warn(error);
      });
  }
  onSelectionChange(selection) {
    this.isAccessible = Object.assign({}, this.isAccessible, selection);
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
  this.isAccessible = false;
  this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
}

openModel(template: TemplateRef<any>,task){
  this.selectedTask = task;
  // if(type=='add'){
    this.startDateAndEndDate= new Date();
    this.assessmentId = task.id;
    this.NewUnitTitle = task.name;
    this.description = task.description;
    this.isAccessible = task.is_accessible;
    console.log(this.isAccessible);
    this.showHistoryTab = task.isPublished;
    this.getPublishHistory(task.id,'assessment');
    console.log('Start Date:'+task.start_date);
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
  // }
}

deleteModel(template: TemplateRef<any>,task){
  this.selectedTask = task;
  this.modalRef = this.modalService.show(template, { class: 'modal-sm' });

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
  this.isAccessible = true;
  this.showHistoryTab = false;
}


  ngOnInit() {
    if(AppSettings.SHOW_COLUMN_TABLE == true) {
      $.FroalaEditor.DefineIcon('insert', { NAME: 'plus' });
      $.FroalaEditor.RegisterCommand('insert', {
        title: 'Add 3 column table',
        focus: true,
        undo: true,
        refreshAfterCallback: true,
  
        callback: function () {
          this.html.insert('<table style="width: 100%;"><thead><tr><th style="text-align: center;">Adjusted</th><th style="text-align: center;">Core</th><th style="text-align: center;">Extended</th></tr></thead><tbody><tr><td style="width: 33.3333%;"><br></td><td style="width: 33.3333%;"><br></td><td style="width: 33.3333%;"><br></td></tr></tbody></table>');
        }
      });
    }
    this.standardTreeTitle = 'Standards';
    this.impactTreeTitle='Educational Aims';
    this.standardBlockTitle = 'What are the learning goals to be addressed';
    /* Course data */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        
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
      console.log(selectedDateRangeValue);
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

  dateChange(event){
    this.publishButton = true;
  }

}
