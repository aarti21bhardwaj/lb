import { Component, OnInit, Input, OnChanges } from '@angular/core';
import { Chart } from 'angular-highcharts';
import { AnalyticsService } from '../../../services/analytics/analytics.service';

@Component({
  selector: 'app-course-strand-distribution',
  templateUrl: './course-strand-distribution.component.html',
  styleUrls: ['./course-strand-distribution.component.scss']
})
export class CourseStrandDistributionComponent implements OnInit {
  @Input() title: string;
  @Input() courseId: any;
  data: any = [];
  chart: any;
  otherStandards: any;

  constructor(public analyticsService: AnalyticsService) {
  }

  ngOnInit() {
    if (this.courseId != null && typeof this.courseId != 'undefined') {
      this.analyticsService.getCourseStrandsDistribution(this.courseId).subscribe((res) => {
        const data: any = res;
        if (data != null) {
          this.data = data.standards;
          this.otherStandards = data.other_standards;
          console.log('Standard usage data' + this.data);
          if (this.data != null && this.data.length > 0) {
            this.getChart();
          }
        }
      }, (error) => console.warn('Error in getting standard usage data' + error)
      );
    }
  }

  // ngOnchanges() {
  // }

  getChart() {
    this.chart = new Chart({
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      title: {
        text: this.title
      },
      tooltip: {
        pointFormat: '% of {series.name}: <b>{point.percentage:.1f}%</b><br> # of {series.name}:<b>{point.y}</b>'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: false,
            format: '<b>%age of {series.name}</b>: <b>{point.percentage:.1f}%</b><br>No. of {series.name}: <b>{point.y}</b>',
            // style: {
            //   color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
            // }
          },
          showInLegend: true
        },
        series: {
          events: {
            legendItemClick: function () {
              return false;
            }
          }
        }
      },
      series: [{
        name: 'Standards',
        data: this.data
      }]
    });
  }
}
