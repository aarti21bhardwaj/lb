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
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { ImpactsComponent } from '../../../shared/impacts/impacts.component';
import { PerformanceTaskService } from '../../../../../../../services/foundation/units/performance-task/performance-task.service';
import { Pipe, PipeTransform } from '@angular/core';
import { DatePipe } from '@angular/common';
import { stagger } from '@angular/core/src/animation/dsl';
import { CoursesService } from './../../../../../../../services/foundation/courses/courses.service';
import { UnitsService } from './../../../../../../../services/foundation/units/units.service';
import { TransferComponent } from '../../../transfer/transfer.component';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
declare var tinymce: any;


@Component({
  selector: 'app-performance-task',
  templateUrl: './performance-task.component.html',
  styleUrls: ['./performance-task.component.scss'],
  providers: [DatePipe]
})
export class PerformanceTaskComponent implements  OnInit {
  categoryId: any;
  editor;
  modalRef: BsModalRef;
  
  @Input() elementId: String;
  @Output() onEditorContentChange = new EventEmitter();
  
  spinnerEnabled: boolean = false;
  unitId;
  courseId;
  assessmentId;
  NewUnitTitle;
  NewUnitDateRange:any;
  saveTask;
  taskList:any;
  resources: any;
  description:any=null;
  selectedTask:any;
  modalBody:string;
  courseDetails: any;
  bsValue: Date = new Date();
  transferGoals: any;
  unitCheckedData: any;
  NewpublishDateRange:any;
  startDateAndEndDate = new Date();
  disableButton: boolean = false;
  publishedHistory:any;
  publishButton = false;  
  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough',
      'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color',
      'align',
      'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
      'insertLink', 'insertImage', 'insertVideo', 'embedly',
      'insertTable', '|',
      'specialCharacters',
      'selectAll', 'clearFormatting', '|',
      'spellChecker',
      'undo', 'redo']
  }
  standardTreeTitle: any;
  standardBlockTitle: any;
  impactTreeTitle:any;
  mode = 'highlight';
  unitData:any;
  showHistoryTab = false;
  
  transform(date): any {
    return this.datePipe.transform(date, 'YYY-MM-DDTHH:mm:ssZ');   
  }
  constructor(
    private performanceService: PerformanceTaskService,
    private acivatedRoute: ActivatedRoute,
    private element: ElementRef,
    private modalService: BsModalService,
    private datePipe: DatePipe,
    private courseService: CoursesService,
    private unitService: UnitsService,
    private parent: TransferComponent,
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
                              this.NewUnitDateRange? (new Date(this.NewUnitDateRange[1])).toDateString():'',
                              this.unitId,
                              this.description,
                              this.courseId,
                              this.assessmentId,
                              5,
                              1
                            )
      .subscribe((response) => {
        this.courseService.refreshUnitSummary();
        this.toastr.success('Task Saved!', 'Success!');
        let newTask:any;
        newTask = response;
        console.log(newTask);
        newTask = newTask.data;
        if(this.assessmentId){
          let index:any;
          index=this.taskList.map(function(x){ return x.id; }).indexOf(newTask.id);
          this.taskList.splice(index,1);
          this.taskList.push(newTask);
        }else{
          this.assessmentId = newTask.id; 
          console.log(this.assessmentId);
          this.NewUnitTitle = newTask.name; 
          console.log(this.NewUnitTitle);
          this.taskList.push(newTask);
        }  
        console.log(this.taskList);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
        this.toastr.error('Task could not be saved', 'Error!');
        console.warn(error);
      });
  }
  deleteModel(template: TemplateRef<any>,task){
    this.selectedTask = task;
    this.modalRef = this.modalService.show(template, { class: 'modal-sm' });
  }
  deleteTask(){
    let task =this.selectedTask;
    this.performanceService.deleteTask(
                              this.courseId,
                              this.unitId,
                              task.id 
                            )
      .subscribe((response) => {
    this.courseService.refreshUnitSummary();

        let newTask:any;
        newTask = response;  
          let index:any;
          index=this.taskList.map(function(x){ return x.id; }).indexOf(task.id);
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
                courseId,unitId,5)
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
  this.selectedTask=null;
  this.assessmentId=null;
  this.NewUnitTitle=null;
  this.description = null;
  this.NewUnitDateRange=null;  
  this.modalRef = this.modalService.show(template, { class: 'modal-lg' }); 
}

openModel(template: TemplateRef<any>,task){
  this.startDateAndEndDate= new Date();
  this.selectedTask = task;
  this.assessmentId = task.id;
  this.NewUnitTitle = task.name;
  this.description = task.description;
  this.showHistoryTab = task.isPublished;
  this.getPublishHistory(task.id,'assessment');
  // if (this.assessmentId) {
  //   this.unitCheckedData = null;
  //   this.getAssessmentContent();
  // }
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

closeTaskModel(template){
  this.modalService.hide(template);
  this.selectedTask = null;
  this.assessmentId = null;
  this.NewUnitTitle = null;
  this.description = null;
  this.NewUnitDateRange = null;
  this.showHistoryTab = false;
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


  taskResourceForm: boolean;

  ngOnInit() {
    this.standardTreeTitle = 'Standards';
    this.impactTreeTitle='Impacts';
    this.standardBlockTitle = 'What learning goals will you include in your summative assessment criteria and feedback?';
    /* Course data */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'common_transfer_goals') {
            console.log('In if when both types are equal');
            this.categoryId = element.id;
            this.unitService.getUnitContent(
              this.courseId, this.unitId, this.categoryId)
              .subscribe((res) => {
                this.transferGoals = res;
                this.transferGoals = this.transferGoals.response.data;
              }, (error) => {
                console.warn(error);
              });
          }
        })
      }, (error) => {
        console.warn(error);
      });
  } /* Init ends here */

  public showTaskResourceForm() {
    this.taskResourceForm = true;
  }

  public saveTaskResource() {
    this.taskResourceForm = false;
  }

  checkSpecificContent(node) {
    console.log(this.unitCheckedData);
    console.log('here is node specific info');
    console.log(node);
    console.log('node info specific ends');
    for (let x in this.unitCheckedData) {
      if (this.unitCheckedData[x].unit_specific_content_id == node.id) {
        node.checked = true;
        break;
      }
    }
  }

  getPublishHistory(objectId, objectType){
    this.publishedHistory = null;
    this.courseService.getPublishHistory(objectId,objectType)
                .subscribe((res) => {
                        this.publishedHistory = res;
                          this.publishedHistory = this.publishedHistory.data;
                }, (error) => {
                          console.warn(error);
              });
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
        this.toastr.error('Unable to publish assessment. Error Message:'+ JSON.stringify(error), 'Error!');
        this.disableButton= false;
      });
    }  
  }

  checked(e, id, type) {
    console.log('any event happend' + e.target);
    if (e.target.checked) {
      console.log('when checked box is checked' + id);
      this.unitService.addAssessmentContents(this.courseId, this.unitId, this.categoryId, id, this.assessmentId, type).subscribe((res) => {
        console.log(res);
        this.toastr.success('Saved!', 'Success!');
      }, (error) => {
        this.toastr.error(error,'Error!');
        console.warn(error);
      });
    }
    if (!e.target.checked) {
      console.log('when checked box is unchecked' + id);
      this.unitService.deleteAssessmentContents(this.courseId, this.unitId, this.categoryId, id, this.assessmentId, type).subscribe((res) => {
        console.log(res);
        this.toastr.success('Deleted Successfully', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
  }
  // alertMe(): void {
  //   setTimeout(function(): void {
  //     alert("You've selected the alert tab!");
  //   });
  // }

  dateChange(event){
     this.publishButton = true;
   }

}
