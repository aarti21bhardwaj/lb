import { Component, OnInit, Input } from '@angular/core';
import { UnitsService } from '../../../../../../services/foundation/units/units.service';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';

@Component({
  selector: 'app-common-content-unit-summary',
  templateUrl: './common-content-unit-summary.component.html',
  styleUrls: ['./common-content-unit-summary.component.scss']
})
export class CommonContentUnitSummaryComponent implements OnInit {
  checkedContents: any;
  specificContents: any ;
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() categoryId: number;
  @Input() commonContents: any;
  constructor(private unitService: UnitsService, private performanceService: PerformanceTaskService) { }

  ngOnInit() {
    if (this.courseId && this.categoryId) {
      this.getCheckedData();
      this.getUnitSpecificContents();
    }
  }

  getCheckedData() {
    this.unitService.getUnitContent(
      this.courseId, this.unitId, this.categoryId)
      .subscribe((res) => {
        this.checkedContents = res;
        this.checkedContents = this.checkedContents.response.data.unit_contents;
      }, (error) => {
        console.warn(error);
      });
  }

  getUnitSpecificContents() {
    this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.categoryId).subscribe((res) => {
      this.specificContents = res;
      this.specificContents = this.specificContents.response.data.unit_specific_contents;
    }, (error) => {
      console.warn('Error in adding unit content' + error);
    });
  }

}
