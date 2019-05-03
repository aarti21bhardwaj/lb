import { Component, OnInit, Input, ViewContainerRef, OnChanges } from '@angular/core';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';


@Component({
  selector: 'app-select-assessment-content',
  templateUrl: './select-assessment-content.component.html',
  styleUrls: ['./select-assessment-content.component.scss']
})
export class SelectAssessmentContentComponent implements OnInit {
  @Input() commonContent: any;
  @Input() courseId: any;
  @Input() unitId: any;
  @Input() categoryId: any;
  @Input() assessmentId: any;
  @Input() assessments: any;
  @Input() type: any;
  NewUnitTitle;
  NewUnitDateRange:any;
  isTree: any=false;
  description:any=null;
  unitCheckedData: any;
  isAccessible : boolean;
  taskList:any;
  constructor(private unitService: UnitsService, public toastr: ToastsManager, private performanceService: PerformanceTaskService,
    vcr: ViewContainerRef) { 
    }

  ngOnInit() {
    console.log("this.commonContent");

    console.log(this.commonContent);
    if(this.type == 'tree' ){
      this.isTree=true;
    }
  }

  checkCommonContent(node) {
    let assessmentIndex: any;
    if ( node) {
      if (this.assessments) {
        assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
        if (this.assessments[assessmentIndex].assessment_standards) {
          for (let x in this.assessments[assessmentIndex].assessment_contents) {
            if (this.assessments[assessmentIndex].assessment_contents[x].content_value_id == node.id) {
              return true;
            }
          }
        }
      }
    }
  }

  checkSpecificContent(node) {
    let assessmentIndex: any;
    if (node) {
      if (this.assessments) {
        assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
        if (this.assessments[assessmentIndex].assessment_contents) {
          for (let x in this.assessments[assessmentIndex].assessment_contents) {
            if (this.assessments[assessmentIndex].assessment_contents[x].unit_specific_content_id == node.id) {
              return true;
            }
          }
        }
      }
    }
  }

  /* for commmon content */
  checkedCommonContent(e, commonContent, type) {
    if (e.target.checked) {
      this.unitService.addAssessmentContents(this.courseId, this.unitId, this.categoryId, commonContent.id, this.assessmentId, type).
      subscribe((res) => {
        this.toastr.success('Saved!', 'Success!');
        let assessmentIndex: any;
        assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
        let index: any;
        index = this.assessments[assessmentIndex].assessment_contents.
          map(function (x) { return x.content_value_id; }).indexOf(commonContent.id);
        if (index) {
          let content: any;
          content = res;
          content = content.response.data;
          this.assessments[assessmentIndex].assessment_contents.push(content);
        }
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
    if (!e.target.checked) {
      this.unitService.deleteAssessmentContents(this.courseId, this.unitId, this.categoryId, commonContent.id, this.assessmentId, type)
        .subscribe((res) => {
          let assessmentIndex: any;
          assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
          let index: any;
          index = this.assessments[assessmentIndex].assessment_contents.
            map(function (x) { return x.content_value_id; }).indexOf(commonContent.id);
          this.assessments[assessmentIndex].assessment_contents.splice(index, 1);
          commonContent.checked = false;
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }

  checkedSpecificContent(e, specificContent, type) {
    if (e.target.checked) {
      this.unitService.addAssessmentContents(this.courseId, this.unitId, this.categoryId, specificContent.id, this.assessmentId, type).
        subscribe((res) => {
          this.toastr.success('Saved!', 'Success!');
          let assessmentIndex: any;
          assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
          let index: any;
          index = this.assessments[assessmentIndex].assessment_contents.
            map(function (x) { return x.unit_specific_content_id; }).indexOf(specificContent.id);
          if (index) {
            let content: any;
            content = res;
            content = content.response.data;
            this.assessments[assessmentIndex].assessment_contents.push(content);
          }
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
    if (!e.target.checked) {
      this.unitService.deleteAssessmentContents(this.courseId, this.unitId, this.categoryId, specificContent.id, this.assessmentId, type)
        .subscribe((res) => {
          this.toastr.success('Deleted Successfully', 'Success!');
          let assessmentIndex: any;
          assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
          let index: any;
          index = this.assessments[assessmentIndex].assessment_contents.
            map(function (x) { return x.unit_specific_content_id; }).indexOf(specificContent.id);
          this.assessments[assessmentIndex].assessment_contents.splice(index, 1);
          specificContent.checked = false;
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }


  createTask(value){
    console.log('value');
    console.log(value);
    this.isAccessible = value;
    console.log(this.isAccessible);
    // this.performanceService.addNewTask(
    //   this.NewUnitTitle,
    //   (new Date(this.NewUnitDateRange[0])).toDateString(),
    //   (new Date(this.NewUnitDateRange[1])).toDateString(),
    //   this.unitId,
    //   this.description,
    //   this.courseId,
    //   this.assessmentId,
    //   4,
    //   this.isAccessible  
    // )
    //   .subscribe((response) => {
    //   let newTask:any;
    //   newTask = response;
    //   if(this.assessmentId){
    //   let index:any;
    //   index=this.taskList.map(function(x){ return x.id; }).indexOf(newTask.data.id);
    //   this.taskList.splice(index,1);
    //   this.taskList.push(newTask.data);
    //   }else{
    //   this.assessmentId = newTask.data.id; 
    //   this.taskList.push(newTask.data);
    //   }

    //   console.log(this.taskList);
    //   // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
    //   }, (error) => {
    //   console.warn(error);
    //   });
     
  }
}
