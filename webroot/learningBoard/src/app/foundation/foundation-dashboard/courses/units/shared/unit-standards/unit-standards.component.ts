import { Component, OnInit, Input, ViewChild, OnChanges, ViewContainerRef } from '@angular/core';
import { TreeModule, TreeNode } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { CoursesService } from './../../../../../../services/foundation/courses/courses.service';

@Component({
  selector: 'app-unit-standards',
  templateUrl: './unit-standards.component.html',
  styleUrls: ['./unit-standards.component.scss']
})
export class UnitStandardsComponent implements OnInit {
  gradeId: any;
  learningAreaId: any;
  listStandardData: any;
  curriculums: any = [];
  grades: any = [];
  learningAreas: any = [];
  isCollaped = true;
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() title: any;
  @Input() treeTitle: any;
  selectedStandards: any;
  addStandardsFlag = false;
  private disabled = false;
  private _disabledV = '0';

  tree: any;
  academicStandards: any;
  private get disabledV() {
    return this._disabledV;
  }
  constructor(private performanceService: PerformanceTaskService, public toastr: ToastsManager,
    vcr: ViewContainerRef, public courseService: CoursesService) {
    this.toastr.setRootViewContainerRef(vcr);
     }

  ngOnInit() {
    if (this.unitId) {
      this.getStandards();
      this.getSelectedUnitStandards();
      this.listAddMoreStandardsData();
    // this.getSelectedStandards();
    }

    if (!this.title) {
      this.title = 'Standards';
    }
    if (!this.treeTitle) {
      this.treeTitle = 'Academic Standards';
    }
    // this.expandCollapseHeading(true);
  }


  public check(node, checked, event) {

    node.data.checked = checked;
    if (event.target.checked) {
      this.performanceService.addUnitStandards(this.courseId, this.unitId, node.data.id)
        .subscribe((response) => {
          if (this.selectedStandards && this.selectedStandards.length > 0) {
            let testFlag = 1;
            let standard: any;
            this.selectedStandards.forEach(element => {
              if (element.standard_id === node.data.id) {
                testFlag = 0;
              }
            });
            if( testFlag == 1 ) {
              standard = response;
              standard = standard.response.data;
              this.selectedStandards.push(standard);
            }
          }else {
            let standard: any;
            standard = response;
            standard = standard.response.data;
            this.selectedStandards.push(standard);
          }
          this.courseService.refreshUnitSummary();
          this.toastr.success('Saved!', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    } else {
      this.performanceService.deleteUnitStandard(this.courseId, this.unitId, node.data.id)
        .subscribe((response) => {
          if (this.selectedStandards && this.selectedStandards.length > 0) {
            this.selectedStandards.forEach(element => {
              if (element.standard_id === node.data.id) {
                this.selectedStandards.splice(this.selectedStandards.indexOf(element), 1);
              }
            });
            // let index: any;
            // index = this.selectedStandards.map(function (x) { return x.standard_id; }).indexOf(node.data.id);
            // this.selectedStandards.splice(index, 1);
          }
          node.data.checked = false;
          this.courseService.refreshUnitSummary();
          // this.getSelectedUnitStandards();
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }

  checkSelectedNode(node) {
    if (node.is_selectable) {
      if (this.selectedStandards) {
        this.selectedStandards.forEach(element => {
          if (element.standard_id === node.id) {
            node.checked = true;
            return true;
          }
        });
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

  getStandards() {
    this.performanceService.getUnitStandards(this.unitId)
      .subscribe((response) => {
        let academicData: any;
        academicData = response;
        this.academicStandards = academicData.unit_standard_sets;
      }, (error) => {
        console.warn(error);
      });
  }

  getSelectedUnitStandards() {
    this.performanceService.getSelectedUnitStandards(this.courseId, this.unitId)
      .subscribe((res) => {
        const standards: any = res;
        this.selectedStandards = standards.response.data;
      }, (error) => {
        console.log('Error in selected Node', error);
      });

  }

  listAddMoreStandardsData() {
    this.performanceService.getSelectedStandards()
      .subscribe((response) => {
        this.listStandardData = response;
        this.listStandardData.grades.forEach(element => {
          this.grades.push({ id: element.id, text: element.name });
        });
        this.listStandardData.curriculums.forEach(element => {
          this.curriculums.push({ id: element.id, text: element.name });
        });
      }, (error) => {
        console.warn(error);
      });
  }

  public showAddStandardsForm() {
    this.addStandardsFlag = true;
  }
  public refreshValue(value: any, field: any): void {
    if (field === 'curriculum') {
      this.learningAreas = [];
      // alert(value.id);
      this.listStandardData.curriculums.forEach(element => {
        if (element.id === value.id) {
          element.learning_areas.forEach(element1 => {
            this.learningAreas.push({ id: element1.id, text: element1.name });
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
      console.log('selected grade'+ this.gradeId);
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

  addMoreUnitStrands() {
    console.log('in adding standard' + this.gradeId)
    this.performanceService.addMoreUnitStandards(this.courseId, this.unitId, this.learningAreaId, this.gradeId)
      .subscribe((response) => {

        // As assessment strands are added, we fetch the standards again
        this.getStandards();
        // Hide the form to add more assessment strands
        this.addStandardsFlag = false;
        this.courseService.refreshUnitSummary();
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
