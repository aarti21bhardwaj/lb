import { Component, OnInit, Input, ViewChild, OnChanges, ViewContainerRef } from '@angular/core';
import { TreeModule, TreeNode } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { CoursesService } from './../../../../../../services/foundation/courses/courses.service';

@Component({
  selector: 'app-unit-impacts',
  templateUrl: './unit-impacts.component.html',
  styleUrls: ['./unit-impacts.component.scss']
})
export class UnitImpactsComponent implements OnInit {
  isCollaped = true;
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() title: any;
  @Input() treeTitle: any;
  selectedImpacts: any;
  tree: any;
  impacts: any;

  constructor(private performanceService: PerformanceTaskService, public toastr: ToastsManager,
    vcr: ViewContainerRef, public courseService: CoursesService) { }

  ngOnInit() {
    if (this.unitId) {
      this.getImpacts();
      this.getSelectedUnitImpacts();
    }
  }

  getImpacts() {
    this.performanceService.getImpacts(this.courseId)
      .subscribe((response) => {
        let impacts: any;
        impacts = response;
        if (impacts != null) {
          this.impacts = impacts.data;
        }
      }, (error) => {
        console.warn(error);
      });
  }

  public check(node, checked, event) {
    node.data.checked = checked;
    if (event.target.checked) {
      this.performanceService.addUnitImpact(this.courseId, this.unitId, node.data.id)
        .subscribe((response) => {
          this.toastr.success('Saved!', 'Success!');
  
          if (this.selectedImpacts && this.selectedImpacts.length > 0) {
            let testFlag = 1;
            let impact: any;
            this.selectedImpacts.forEach(element => {
              if (element.impact_id === node.data.id) {
                testFlag = 0;
              }
            });
            if (testFlag == 1) {
              impact = response;
              impact = impact.response.data;
              this.selectedImpacts.push(impact);
            }
          } else {
            let impact: any;
            impact = response;
            impact = impact.response.data;
            this.selectedImpacts.push(impact);
          }
          this.courseService.refreshUnitSummary();
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    } else {
      this.performanceService.deleteUnitImpact(this.courseId, this.unitId, node.data.id)
        .subscribe((response) => {
          if (this.selectedImpacts && this.selectedImpacts.length > 0) {
            this.selectedImpacts.forEach(element => {
              if (element.impact_id === node.data.id) {
                this.selectedImpacts.splice(this.selectedImpacts.indexOf(element), 1);
              }
            });
          }
          node.data.checked = false;
          this.courseService.refreshUnitSummary();
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }

  checkSelectedNode(node) {
    if (node.is_selectable) {
      if (this.selectedImpacts) {
        this.selectedImpacts.forEach(element => {
          if (element.impact_id === node.id) {
            node.checked = true;
            return true;
          }
        });
      }
    }
  }

  getSelectedUnitImpacts() {
    this.performanceService.getSelectedUnitImpacts(this.courseId, this.unitId)
      .subscribe((res) => {
        const impacts: any = res;
        if (impacts != null) {
          this.selectedImpacts = impacts.response.data;
        }
      }, (error) => {
        console.log('Error in selected Node', error);
      });

  }

  expandAll(tree) {
    this.isCollaped = false;
    tree.expandAll();
  }

  collapseAll(tree) {
    this.isCollaped = true;
    tree.collapseAll();
  }

}
