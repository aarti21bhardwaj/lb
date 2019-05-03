import { Component, OnInit, Input } from '@angular/core';
import { Chart } from 'angular-highcharts';
import * as Highcharts from 'highcharts';
import { AnalyticsService } from '../../../services/analytics/analytics.service';
// import { HighchartsMore } from 'highcharts/highcharts-more';
declare var require: any;
require('highcharts/highcharts-more')(Highcharts);
require('highcharts/modules/solid-gauge')(Highcharts);
require('highcharts/modules/heatmap')(Highcharts);
require('highcharts/modules/treemap')(Highcharts);
require('highcharts/modules/funnel')(Highcharts);


@Component({
  selector: 'app-polar-chart',
  templateUrl: './polar-chart.component.html',
  styleUrls: ['./polar-chart.component.scss']
})
export class PolarChartComponent implements OnInit {
  courseList: any;
  @Input() sectionId;
  @Input() termId;
  @Input() studentId;
  @Input() type;
  @Input() showButtonFlag = false;
  @Input() chartType;
  @Input() courseId;
  @Input() title;
  chart: any;
  colors: any = [];
  chartData: any;
  categories : any = false ;
  series: any = [];
  constructor(public analyticsService: AnalyticsService) { }

  ngOnInit() {
    if (this.chartType === 'Common') {
      console.log('In common data');
      this.getChartData();
    }
    if (this.chartType === 'CourseSpecific') {
      this.getCourseSpecificChartData();
      console.log('specific data');
    }
  }

  getChartData() {
    this.analyticsService.getCircumplexData(this.termId, this.studentId, this.type
    ).subscribe(response => {
      console.log('chart Data' + response);
      let data: any;
      data = response;
      data = data.response;

      if (data != null && typeof data != 'undefined') {
        this.categories = [];
        this.series = [];
        this.categories = data.categories;
        this.series = data.series;
        this.courseList = data.courseList;
        this.colors = data.colors;
        this.getPolarChart();
      }
      }, (error) => {
        console.warn('Error in getting chart data' + error);
        this.categories = false;
      }
  );
  }

  getCourseSpecificChartData() {
    this.analyticsService.getCourseCircumplex(this.termId, this.studentId, this.courseId
    ).subscribe(response => {
      console.log('chart Data' + response);
      let data: any;
      data = response;
      data = data.response;

      if (data != null && typeof data!= 'undefined') {
        this.categories = data.categories;
        this.series = data.series;
        this.getPolarChart();
      }
      }, (error) => {console.warn('Error in chart data' + error);
          this.categories = false;
    }
  );
  }

  getPolarChart() {
    this.chart = new Chart({
    // this.chart = Highcharts.chart('polarChart', {
      chart: {
        polar: true
      },

      title: {
        text: 'Circumplex'
      },
      tooltip: {
        formatter: function () {
          return this.series.name + ', Score: ' + this.y;
        }
      },


      xAxis: [{
        categories: this.categories,
        min: 0,
        lineWidth: 0
      }],

      yAxis: [{
        min: 0,
        tickInterval: 1,
        /* gridLineInterpolation: 'polygon', */
        labels: {
          enabled: false
        }
      }],


      plotOptions: {

        column: {
          pointPadding: 0,
          groupPadding: 0,
          stacking: 'normal'
        },
        series: {
          stacking: 'normal',
          events: {
            legendItemClick: function () {
              return false;
            }
          }
        }
      },

      series: this.series
    });
  }
  


}
