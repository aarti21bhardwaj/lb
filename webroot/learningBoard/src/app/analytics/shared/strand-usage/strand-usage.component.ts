import { Component, OnInit, Input } from '@angular/core';
import { AnalyticsService } from '../../../services/analytics/analytics.service';
import { Chart } from 'angular-highcharts';

@Component({
  selector: 'app-strand-usage',
  templateUrl: './strand-usage.component.html',
  styleUrls: ['./strand-usage.component.scss']
})
export class StrandUsageComponent implements OnInit {
@Input() courseId: any;
@Input() title: string;
categories: any = [];
selectedData: any;
unSelectedData: any;
data: any;
barChart: any;
  constructor(public analyticsService: AnalyticsService) {
    console.log('In standard usage component');
   }

  ngOnInit() {
    if (this.courseId != null && typeof this.courseId != 'undefined') {
      this.analyticsService.getStandardUsage(this.courseId).subscribe((res) => {
        const data: any = res;
        if (data != null) {
          this.categories = data.data.strand_names;
          this.selectedData = data.data.selected_standards;
          this.unSelectedData = data.data.unselected_standards;
          console.log('Standard usage categories' + this.categories);
          if (this.categories != null && this.categories.length > 0) {
            this.getStandardUsage();
          }
        }
      }, (error) => console.warn('Error in getting standard usage data' + error)
      );
    }
  }

  getStandardUsage() {
    this.barChart = new Chart({
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'bar'
      },
      title: {
        text: this.title
      },
      tooltip: {
        pointFormat: '% of {series.name}</b>: <b>{point.percentage:.1f}%<br>Count of {series.name}: {point.value}'
      },
      xAxis: {
        // categories: ['Geomectory', 'Arithematics', 'Calculus', 'Graphs', 'Mathematics1']
        categories: this.categories,
        // max: 100
      },
      yAxis: {
        min: 0,
        title: {
          text: this.title
        },
        max: 100
      },
      legend: {
        reversed: true
      },
      plotOptions: {
        series: {
          stacking: 'normal',
          events: {
            legendItemClick: function () {
              return false;
            }
          }
        }
      },
      series: [{
        name: 'Unused standards',
        color: 'red',
        data: this.unSelectedData
      },
      {
        name: 'Used standards',
        color: 'green',
        data: this.selectedData
      }]
    });
  }

}
