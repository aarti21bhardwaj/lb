import { Component, OnInit, Input, DoCheck } from '@angular/core';
import { UnitsService } from '../../../../../../services/foundation/units/units.service';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';

@Component({
  selector: 'app-unit-summary',
  templateUrl: './unit-summary.component.html',
  styleUrls: ['./unit-summary.component.scss']
})
export class UnitSummaryComponent implements OnInit {
  @Input() courseId: any;
  @Input() unitId: any;
  @Input() categoryId: any;
  @Input() title: any;
  @Input() type: any;
  @Input() isExpanded = false;
  @Input() assessmentTypeId;
  unitSpecificContents: any;
  selectedStandards: any;
  selectedImpacts: any;
  taskList:any;
  constructor(private unitService: UnitsService, private performanceService: PerformanceTaskService) { }

  ngOnInit() {
    if (this.courseId && this.unitId) {
      if (this.type == 'SpecificContent') {
        this.getUnitSpecificContents();
      }
      if (this.type == 'Standard') {
        this.getSelectedUnitStandards();
      }
      if (this.type == 'Impact') {
        this.getSelectedUnitImpacts();
      }
      console.log('Assessment type: '+this.assessmentTypeId)
      if (this.type == 'Assessment'&& this.assessmentTypeId) {
        console.log('here');
        this.getAssessmentContent(this.assessmentTypeId);
      }
    }
  }


  // ngDocheck() {
  //   console.log('In unit summary');
  //   if (this.courseId && this.unitId) {
  //     if (this.type == 'SpecificContent') {
  //       this.getUnitSpecificContents();
  //     }
  //     if (this.type == 'Standard') {
  //       this.getSelectedUnitStandards();
  //     }
  //     if (this.type == 'Impact') {
  //       this.getSelectedUnitImpacts();
  //     }
  //   }
  // }

  getUnitSpecificContents() {
    this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.categoryId).subscribe((res) => {
      const specificData: any = res;
      if (specificData.response.data != null) {
        this.unitSpecificContents = specificData.response.data.unit_specific_contents;
      }
    }, (error) => {
      console.warn('Error in adding unit content' + error);
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

  getAssessmentContent(assessmentTypeId) {
    console.log('In getAssessmentContent');
    this.performanceService.getTaskList(this.courseId,this.unitId,assessmentTypeId)
      .subscribe((response) => {
        console.log('response');
        console.log(response);
        let data: any;
        data = response
        this.taskList = data.data;
        console.log('taskList');
        console.log(this.taskList);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
        
      }, (error) => {
            console.warn(error);
      });
  }
}
