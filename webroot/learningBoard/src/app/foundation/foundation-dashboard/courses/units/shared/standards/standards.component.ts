import { Component, OnInit, Input, ViewChild, OnChanges, ViewContainerRef} from '@angular/core';
import { TreeModule,TreeNode } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-standards',
  templateUrl: './standards.component.html',
  styleUrls: ['./standards.component.scss'],
  
})
export class StandardsComponent implements OnInit {

  isCollaped = true;
  @Input() assessmentId: number;
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() assessments: any;
  @Input() title: any;
  @Input() treeTitle: any;
  @Input() unitStandards: any;
  @Input() mode: any;
  learningAreaId: any;
  gradeId: any;
  assessmentStrandsData: any;
  curriculums: any = [];
  learningAreas: any = [];
  grades: any = [];
  addStandardsFlag = false;
  private disabled = false;
  private _disabledV = '0';
  isExpanded = false;
  tree:any;
  academicStandards: any;
  private get disabledV() {
    return this._disabledV;
  }

  constructor(private performanceService: PerformanceTaskService, public toastr: ToastsManager,
    vcr: ViewContainerRef) {
     }
  
  ngOnInit() {
    console.log('In shared component of standards');
    if (this.unitStandards.unit != null && typeof this.unitStandards.unit != 'undefined') {
      this.unitStandards = this.unitStandards.unit.unit_standards;
    }
    if(!this.mode){
      this.mode ='default';
    }
    if(this.assessmentId){
      this.getStandards(this.assessmentId);
    }
    this.getSelectedStandards();
    
    if(!this.title){
      this.title = 'Standards';
    }
    if(!this.treeTitle){
      this.treeTitle = 'Academic Standards'; 
    }
  }

  ngOnChanges() {
  }
 
  public check(node, checked, event) {
    if (event.target.checked) {
      this.performanceService.selectStandards(this.courseId,this.unitId, this.assessmentId, node.data.id)
        .subscribe((response) => {
          let assessmentIndex:any;
          assessmentIndex=this.assessments.map(function(x){ return x.id; }).indexOf(this.assessmentId);
          let index:any;
          index=this.assessments[assessmentIndex].assessment_standards.map(function(x){ return x.standard_id; }).indexOf(node.data.id);
          if(index){
            let standard:any;
            standard = response;
            standard = standard.data;
            this.assessments[assessmentIndex].assessment_standards.push(standard);
          }
          this.toastr.success('Saved!', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    } else {
      this.performanceService.unselectStandards(this.courseId,this.unitId, this.assessmentId, node.data.id)
        .subscribe((response) => {
          let assessmentIndex:any;
          assessmentIndex=this.assessments.map(function(x){ return x.id; }).indexOf(this.assessmentId);
          let index:any;
          index=this.assessments[assessmentIndex].assessment_standards.map(function(x){ return x.standard_id; }).indexOf(node.data.id);
          this.assessments[assessmentIndex].assessment_standards.splice(index,1);
          node.data.checked = false;
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }
  
  checkSelectedNode(node){
    let assessmentIndex:any;
    if(node.is_selectable && node.code){
      if(this.assessments){
        assessmentIndex=this.assessments.map(function(x){ return x.id; }).indexOf(this.assessmentId);
        if(this.assessments[assessmentIndex].assessment_standards){
      for(let x in this.assessments[assessmentIndex].assessment_standards){
        if(this.assessments[assessmentIndex].assessment_standards[x].standard_id == node.id){
          return true;
        }
      }
    }
      } 
    }
    
  }

  highlightNode(node){
    let testFlag = false;
    this.unitStandards.forEach(element => {
      if (element.standard_id == node.id) {
        testFlag = true;
        //break;
      }
    });
    // if(testFlag){
    //   console.log('higlighted node will be ' );
    //   console.log(node);
    // }else{
    //   console.log('this node will not be highlighted' );
    //   console.log(node);
    // }
    return testFlag;
    
  }
  expandAll(tree) {
    this.isCollaped = false;
    tree.expandAll();
  }

  collapseAll(tree) {
    this.isCollaped = true;
    tree.collapseAll();
  }
  getStandards(assessmentId) {
    this.performanceService.getAssessmentStandards(assessmentId, this.unitId)
      .subscribe((response) => {
        let academicData:any;
        academicData = response;
        this.academicStandards = academicData.standard_sets;
      }, (error) => {
        console.warn(error);
      });
  }

  public showAddStandardsForm(){
    this.addStandardsFlag = true;
  }
  public refreshValue(value: any, field: any): void {
    if (field === 'curriculum') {
      this.learningAreas = [];
      // alert(value.id);
      this.assessmentStrandsData.curriculums.forEach(element => {
        if ( element.id === value.id ){
          element.learning_areas.forEach(element1 => {
            this.learningAreas.push({id:element1.id, text:element1.name});
          });
        }
      });
      // alert(this.learningAreas);
    }

    if (field === 'learningArea') {
      this.learningAreaId = value.id;
      // this.unitData.unit.courses = value;
      console.log(this.learningAreaId);
    }

    if (field === 'grades') {
      this.gradeId = value.id;
      // this.unitData.unit.courses = value;
      console.log(this.gradeId);
    }
  }

  private set disabledV(value: string) {
    this._disabledV = value;
    this.disabled = this._disabledV === '1';
  }
  public selected(value: any, field: any): void {
    console.log('Selected value is: ', value);
  }
  public removed(value: any, field: any): void {
    console.log('Removed value is: ', value);
  }

  getSelectedStandards() {
    this.performanceService.getSelectedStandards()
      .subscribe((response) => {
        this.assessmentStrandsData = response;
        this.assessmentStrandsData.grades.forEach(element => {
          this.grades.push({ id: element.id, text: element.name });
        });
        this.assessmentStrandsData.curriculums.forEach(element => {
          this.curriculums.push({ id: element.id, text: element.name });
        });
      }, (error) => {
        console.warn(error);
      });
  }

  addMoreAssessmentStrands() {
    this.performanceService.addMoreStandards(this.courseId, this.unitId, this.assessmentId, this.learningAreaId, this.gradeId )
      .subscribe((response) => {
        // As assessment strands are added, we fetch the standards again
        if (this.assessmentId) {
          this.getStandards(this.assessmentId);
        }
        // Hide the form to add more assessment strands
        this.addStandardsFlag = false;
        this.toastr.success('Saved!', 'Success!');
      }, (error) => {
        this.toastr.error('No strand available for this learning area and grade.', 'Error!');
        console.warn(error);
      });
  }

  closeAddMoreStandards() {
    this.addStandardsFlag = false;
  }

}
