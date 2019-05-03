import { Component, OnInit, Input, ViewChild, OnChanges, ViewContainerRef } from '@angular/core';
import { TreeModule, TreeNode } from 'angular-tree-component';
import { EvidencesService } from '../../services/evidences/evidences.service';
import { TeacherEvidencesService } from '../../services/teacher-evidences/teacher-evidences.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-impacts-add-tree',
  templateUrl: './impacts-add-tree.component.html',
  styleUrls: ['./impacts-add-tree.component.scss']
})
export class ImpactsAddTreeComponent implements OnInit {
  @Input() courseIds: any = [];
  @Input() evidenceId: number;
  @Input() title: any;
  @Input() treeTitle: any;
  @Input() studentOrTeacher: any;
  selectedImpacts: any;
  tree: any;
  impacts: any;
  evidenceService: any;

  constructor(
    private studentEvidenceService: EvidencesService,
    private teacherEvidence: TeacherEvidencesService,
    public toastr: ToastsManager,
    vcr: ViewContainerRef,
  ) {

    this.toastr.setRootViewContainerRef(vcr);
   }

  ngOnInit() {
    
    console.log('this.studentOrTeacher');
    console.log(this.studentOrTeacher);
    if (this.studentOrTeacher == 'student') {
      console.log(this.studentOrTeacher);
      this.evidenceService = this.studentEvidenceService
    }

    if (this.studentOrTeacher == 'teacher') {
      console.log(this.studentOrTeacher);
      this.evidenceService = this.teacherEvidence;
    }

    if (this.evidenceId) {
      this.getImpacts();
      this.getSelectedUnitImpacts();
    }
  }

  getImpacts() {
    console.log(this.evidenceService);
    this.evidenceService.getImpacts(this.courseIds)
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

  public check(node, checked) {
    
    node.data.checked = checked;
    if (checked) {
      this.evidenceService.addUnitImpact(this.evidenceId, node.data.id)
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
              console.log('i m new standard');
              console.log(impact);
              this.selectedImpacts.push(impact);
            }
          } else {
            let impact: any;
            impact = response;
            impact = impact.response.data;
            this.selectedImpacts.push(impact);
          }
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    } else {
      this.evidenceService.deleteUnitImpact(this.evidenceId, node.data.id)
        .subscribe((response) => {
          if (this.selectedImpacts && this.selectedImpacts.length > 0) {
            this.selectedImpacts.forEach(element => {
              if (element.impact_id === node.data.id) {
                this.selectedImpacts.splice(this.selectedImpacts.indexOf(element), 1);
              }
            });
          }
          node.data.checked = false;
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
    }
  }

  checkSelectedNode(node) {
    console.log('In check' + '-----> Selected Node' + node);
    if (node.selectable) {
      if (this.selectedImpacts) {
        this.selectedImpacts.forEach(element => {
          if (element.impact_id === node.id) {
            console.log('standard Id' + element.impact_id);
            console.log('node Id ' + node.id);
            node.checked = true;
            return true;
          }
        });
      }
    }
  }

  getSelectedUnitImpacts() {
    this.evidenceService.getSelectedUnitImpacts(this.evidenceId)
      .subscribe((res) => {
        console.log('res');
        const impacts: any = res;
        if (impacts != null) {
          this.selectedImpacts = impacts.response.data;
        }
      }, (error) => {
        console.log('Error in selected Node', error);
      });

  }

}
