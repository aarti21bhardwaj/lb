import { Component, OnInit, Input, ViewContainerRef  } from '@angular/core';
import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';
import { ElementRef } from '@angular/core';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-impacts',
  templateUrl: './impacts.component.html',
  styleUrls: ['./impacts.component.scss']
})
export class ImpactsComponent implements OnInit {
  isCollaped = true;
  @Input() assessmentId: number;
  @Input() title: any;
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() impacts: any;
  @Input() assessments: any;
  @Input() mode: any;
  errorMessage;
  showNoData = false;
  tree: any;

  constructor(private performanceService: PerformanceTaskService, public toastr: ToastsManager,
    vcr: ViewContainerRef) {
    console.log(this.courseId+'---'+this.unitId+'--->'+this.assessmentId);
   }

  ngOnInit() {
    if (this.courseId != null && typeof this.courseId != 'undefined') {
      this.getImpacts(this.courseId);
    }
    if (!this.mode) {
      this.mode = 'default';
    }
  }



  public check(node, checked, event) {
    if (event.target.checked) {
      node.data.checked = checked;
      this.performanceService.selectImpact(this.courseId,this.unitId, this.assessmentId, node.data.id)
        .subscribe((response) => {
          let assessmentIndex: any;
          assessmentIndex = this.assessments.map(function(x){ return x.id; }).indexOf(this.assessmentId);
          let index: any;
          index = this.assessments[assessmentIndex].assessment_impacts.map(function(x){ return x.impact_id; }).indexOf(node.data.id);
          if (index) {
            let impact: any;
            impact = response;
            impact = impact.data;
            this.assessments[assessmentIndex].assessment_impacts.push(impact);
          }
          this.toastr.success('Impact saved!', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    } else {
      node.data.checked = null;
      this.performanceService.unselectImpact(this.courseId,this.unitId, this.assessmentId, node.data.id)
        .subscribe((response) => {
          let assessmentIndex: any;
          assessmentIndex = this.assessments.map(function(x){ return x.id; }).indexOf(this.assessmentId);
          let index: any;
          index = this.assessments[assessmentIndex].assessment_impacts.map(function(x){ return x.impact_id; }).indexOf(node.data.id);
          this.assessments[assessmentIndex].assessment_impacts.splice(index, 1);
          node.data.checked = false;
          this.toastr.success('Impact deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }
  checkSelectedNode(node){
    let impactIndex:any;
    let nodeIndex:any;
    let assessmentIndex:any;
    if (this.assessments && node.is_selectable){
      assessmentIndex=this.assessments.map(function(x){ return x.id; }).indexOf(this.assessmentId);
       if(this.assessments[assessmentIndex].assessment_impacts){
          for(let x in this.assessments[assessmentIndex].assessment_impacts){
            if(this.assessments[assessmentIndex].assessment_impacts[x].impact_id == node.id){
              return true;
            }
          }
       }
    }
  }
  expandAll(tree) {
    this.isCollaped = false;
    tree.expandAll();
  }

  collapseAll(tree) {
    this.isCollaped = true;
    tree.collapseAll();
  }
  getImpacts(courseId) {
    this.performanceService.getImpacts(courseId, this.unitId)
      .subscribe((response) => {
        let impacts:any;  
         impacts = response;
        this.impacts = impacts.data;
        this.showNoData = false;
      }, (error) => {
        this.showNoData = true;
        console.warn(error.message);
        this.errorMessage = error.message;
      });
  }

}
