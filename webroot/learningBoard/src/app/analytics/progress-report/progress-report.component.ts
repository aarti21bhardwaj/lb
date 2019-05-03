import { Component, OnInit, Input, DoCheck, ElementRef, TemplateRef} from '@angular/core';
import { Chart } from 'angular-highcharts';
import { ModalModule } from 'ngx-bootstrap/modal';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import * as Highstock from 'highcharts/highstock';
import { AnalyticsService } from '../../services/analytics/analytics.service';
import { ActivatedRoute } from '@angular/router';
declare var $: any;

@Component({
  selector: 'app-progress-report',
  templateUrl: './progress-report.component.html',
  styleUrls: ['./progress-report.component.scss']
})
export class ProgressReportComponent implements OnInit {
  assessmentData: any;
  units: any;
  modalRef: BsModalRef;
  taskData: any = [];
  experienceData: any = [];
  ActivitiesData: any = [];
  summativeData: any = [];
  formativeData: any = [];
  students: any = [];
  sectionId: any ;
  studentId: any ;
  chart: any;
  splineChart: any;
  title: any= 'yay';
  data: any = [];
  chartData: any;
  assessmentType: any = [];
  splineData: any;
  sparklingArray: any = [];
  allData: any = [];
  selectedTable: string = 'all';
  showSplineChart = false;
  spinnerEnabled = false;
  showBanner = false;
  noData = false;
  getAssessmentType: any = 'all';
  assessmentTypeData:any=[];
  keys:any=[];
  constructor(
    public analyticsService: AnalyticsService,
    private acivatedRoute: ActivatedRoute,
    private modalService: BsModalService,
  ) {
  }

  ngOnInit(){
    console.log('In progress report component')
  }

  ngDoCheck() {
    this.acivatedRoute.params.subscribe(res => {
      const sectId = res.section_id;
      const stId = res.student_id;
      // if (stId != null && typeof stId !== 'undefined') {
      //   // this.showBanner = false;
      // }
      if (stId && this.studentId !== stId) {
        console.log('In if routes matched' + stId);
        this.sectionId = sectId;
        this.studentId = stId;
        // this.spinnerEnabled = true;
        this.getChartData();
        // setTimeout(() => { this.spinnerEnabled = false }, 10000);
      }
    });
   this.students = this.analyticsService.studentList;
   if (this.students.length === 0) {
    //  this.showBanner = true;
   }

  }

  getChartData() {
    // this.chartData = null;
    // this.assessmentType = [];
    this.spinnerEnabled = true;
    this.analyticsService.getChartData(this.sectionId, this.studentId).subscribe(response => {
      console.log('chart Data' + response);
      let data: any;
      data = response;
      this.noData = false;
      this.chartData = data.evaluations;
      this.splineData = data.splineData;
      this.assessmentType = data.assessmentTypes;
      this.assessmentTypeData=data.assessmentTypeData;
      this.keys=Object.keys(this.assessmentTypeData);
      console.log(this.keys);
      this.spinnerEnabled = false
    }, (error) => {
                  // setTimeout(() => { this.spinnerEnabled = false }, 10000);
                  this.spinnerEnabled = false;
                  this.chartData = {};
                  this.assessmentType = [];
                  this.noData = true;
  }
  );
  }

  chartSparkline(id) {
    let x: any = [];
    console.log('In chartsparkline id' + id)
    if (this.splineData[id] != null && (typeof this.splineData[id] != 'undefined') ){
      this.splineData[id].forEach(element => {
        x.push(element[1]);
      });
      console.log('before get chart' + x);
      return this.getChart(x);
    }
  }


  getChart(x) {
     let chart =  new Chart({
      series: [{
        data: x,
      }],

        chart: {
          backgroundColor: null,
          borderWidth: 0,
          type: 'area',
          margin: [2, 0, 2, 0],
          width: 120,
          height: 40,
        },
        title: {
          text: ''
        },
        credits: {
          enabled: false
        },
        xAxis: {
          labels: {
            enabled: false
          },
          title: {
            text: null
          },
          startOnTick: false,
          endOnTick: false,
          tickPositions: []
        },
        yAxis: {
          endOnTick: false,
          startOnTick: false,
          labels: {
            enabled: false
          },
          title: {
            text: null
          },
          tickPositions: [0]
        },
        legend: {
          enabled: false
        },
        tooltip: {
          backgroundColor: null,
          borderWidth: 0,
          shadow: false,
          useHTML: true,
          hideDelay: 0,
          shared: true,
          padding: 0,
          positioner: function (w, h, point) {
            return { x: point.plotX - w / 3, y: point.plotY - h / 2 };
          }
        },
        plotOptions: {
          series: {
            animation: false,
            lineWidth: 1,
            shadow: false,
            states: {
              hover: {
                lineWidth: 1
              }
            },
            marker: {
              radius: 1,
              states: {
                hover: {
                  radius: 2
                }
              }
            },
 //           fillOpacity: 0.25
          },
          column: {
            negativeColor: '#910000',
            borderColor: 'silver'
          }
        }

  });

  return chart;
  }

  getChartSpline(id, standardName) {
    console.log('In getting spline chart. Id is' + id);
    console.log('In spline chart data' + this.splineData[id]);
    this.splineChart = Highstock.stockChart('splinechart', {
      chart: {
        backgroundColor: null,
        type: 'stock',
      },
      rangeSelector: {
        selected: 1
      },

      title: {
        text: 'Progress Chart for ' + standardName
      },
      series: [{
        name: 'Score',
        data: this.splineData[id],
        type: 'spline',
      }]
    }, function (chart) {

      // apply the date pickers
      setTimeout(function () {

        $('input.highcharts-range-selector').datepicker({
          dateFormat: 'yy-mm-dd',
          onSelect: function (dateText) {
            chart.xAxis[0].setExtremes($('input.highcharts-range-selector:eq(0)').datepicker("getDate").getTime(), $('input.highcharts-range-selector:eq(1)').datepicker("getDate").getTime());
            //this.onchange();
            this.onblur();
          }
        });
      }, 1000)
    });
  }


  getTable(type) {

    console.log('in get type function' + type);
    this.getAssessmentType = type;
    console.log('In get table');
    console.log('chart data' + this.chartData);
    // this.chartData.forEach(element => {
    //   // this.allData.push(element.data);

    //   console.log('all data' + this.allData);

    //   if (type === 'Formative Assessments') {
    //     // if(element.assessement_type === type) {
    //       this.formativeData = element.data;
    //     // }
    //   }
    //   if (type === 'Summative Assessments') {
    //     // if (element.assessement_type === type) {
    //       this.summativeData = element.data;
    //     // }
    //   }
    //   if (type === 'Learning Activities') {
    //     // if (element.assessement_type === type) {
    //       this.ActivitiesData = element.data;
    //     // }
    //   }
    //   if (type === 'Learning Experiences') {
    //     // if (element.assessement_type === type) {
    //       this.experienceData = element.data;
    //     // }
    //   }
    //   if (type === 'Performance Tasks') {
    //     // if (element.assessement_type === type) {
    //       this.taskData = element.data;
    //     // }
    //   }
    // });
  }

  viewSpline(id, standardName) {
    this.showSplineChart = true;
    console.log('view' + id);
    setTimeout(() => {
      this.getChartSpline(id, standardName);
      window.scrollTo(0, 0);
    }, 500);
  }

  closeChart() {
    this.showSplineChart = false;
  }

  showViewButton(id) {
    if (this.splineData[id].length > 1) {
      return true;
    } else {
      return false;
    }
  }

  openModel(template: TemplateRef<any>, type, data) {
    if (type == 'Units') {
      this.units = data;
    }
    if (type == 'Evaluations') {
      this.assessmentData = data;
      console.log("this.assessmentData");
      console.log(this.assessmentData);

    }

    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
  }

}
