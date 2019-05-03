import { Component, OnInit, Input } from '@angular/core';
import { AnalyticsService } from '../../../services/analytics/analytics.service';

@Component({
  selector: 'app-standard-usage-table',
  templateUrl: './standard-usage-table.component.html',
  styleUrls: ['./standard-usage-table.component.scss']
})
export class StandardUsageTableComponent implements OnInit {
  @Input() courseId;
  tableData: any = [];
  assessmentTypes: any;
  categories: any;
  isCollapsed: any = {};

  constructor(public analyticsService: AnalyticsService) {
   }

  ngOnInit() {
    if (this.courseId != null && typeof this.courseId != 'undefined') {
      this.analyticsService.getStandardUsage(this.courseId).subscribe((res) => {
        const data: any = res;
        if (data != null) {
          this.categories = data.data.strand_data;
          console.log('These are the categories' + this.categories);
          if (this.categories != null && this.categories > 0) {
          }
        }
      }, (error) => console.warn('Error in getting standard usage data' + error)
      );
    }
  }

  public selected(value: any): void {
    console.log('Selected value is: ', value);
    this.tableData = [];
    this.getStandardsData(value.id);
  }
  public removed(value: any): void {
    console.log('Removed value is: ', value);
  }
  public refreshValue(value: any): void {
    console.log('Refreshed value is: ', value);
  }

  getStandardsData(strandId) {
    this.analyticsService.getStandardsByStrand(this.courseId, strandId).subscribe((res) => {
      const data: any = res;
      this.tableData = data.data;
      this.assessmentTypes = data.assessment_types;
      console.log('getStandardData is' + this.tableData);
    }, (error) => console.warn('Error in getting standard usage data' + error)
    );
  }

}
