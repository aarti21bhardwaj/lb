import { Component, OnInit, ViewContainerRef } from '@angular/core';
import { TeacherService } from '../../services/foundation/teachers/teacher.service';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
declare var $: any;
import { AppSettings } from '../../app-settings';

@Component({
  selector: 'app-reports',
  templateUrl: './reports.component.html',
  styleUrls: ['./reports.component.scss']
})
export class ReportsComponent implements OnInit {

  courseLinks: any;
  savedReflectionId: any = false;
  studentComment: any;
  teacherComment: any;
  studentServices: any = [];
  checkedService: Object;
  spinnerEnabled: boolean;
  studentId: any;
  sectionId: any;
  strandModel: any = {};
  standardModel: any = {};
  impactModel: any = {};
  courseModel: any = {};
  defaultImpactScaleValue: any;
  defaultAcademicScaleValue: any;
  impactScaleValueNameMap: any = {};
  academicScaleValueNameMap: any = {};

  reportSettings: any;
  impacts: any;
  strands: any;
  student: any;
  impactScale: any;
  academicScale: any;
  impactScaleValueCount: number = 0;
  academicScaleValueCount: number = 0;
  evnEndpoint: any;

  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough',
      'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color',
      // 'inlineStyle', 'paragraphStyle',
      // 'paragraphFormat',
      'align',
      'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
      'insertLink', 'insertImage', 'insertVideo', 'embedly',
      // 'insertFile',
      'insertTable', '|',
      // 'emoticons', 
      'specialCharacters',
      // 'insertHR',
      'selectAll', 'clearFormatting', '|',
      // 'print', 
      'spellChecker',
      // 'help', 
      // 'html', '|', 
      'undo', 'redo']
  }

  constructor(
    private teachers: TeacherService,
    private route: ActivatedRoute,
    private router: Router,
    public toastr: ToastsManager,
    vcr: ViewContainerRef
  ) {
    this.toastr.setRootViewContainerRef(vcr);
  }

  ngOnInit() {
    this.studentId = this.route.snapshot.paramMap.get('student_id');
    this.sectionId = this.route.parent.snapshot.paramMap.get('section_id');
    this.evnEndpoint = AppSettings.ENVIRONMENT;
    this.getCourseLinks();
    if(this.studentId && this.sectionId) {
      this.getReflections();
    }
  }

  ngAfterContentInit() {
    // console.log('after view');
    this.getStudentReport();
  }

  slider(selectorName, entityId, min, max, preSetValue, gridnum) {

    const selector = '#ion-slider-' + selectorName + entityId;

    $(selector).ionRangeSlider({
      type: 'single',
      min: min,
      max: max,
      from: preSetValue,
      step: 1,
      grid: true,
      grid_num: gridnum,
      prettify: (data) => {
        if (selectorName == 'impact') {
          if (typeof this.impactScaleValueNameMap[data].label == 'undefined' || this.impactScaleValueNameMap[data].label === null) {
            console.log('here1');
            return this.impactScaleValueNameMap[data].name;
          } else {
            console.log('here2');
            if (this.impactScaleValueNameMap[data].label === '') {
              $(selector).prev('span').find('.irs-single').hide();
            }else{
              $(selector).prev('span').find('.irs-single').show();
            }
            return this.impactScaleValueNameMap[data].label;
          }
        } else {
          if (typeof this.academicScaleValueNameMap[data].label == 'undefined' || this.academicScaleValueNameMap[data].label === null) {
            return this.academicScaleValueNameMap[data].name;
          } else {
            if (this.academicScaleValueNameMap[data].label === '') {
              $(selector).prev('span').find('.irs-single').hide();
            }else{
              $(selector).prev('span').find('.irs-single').show();
            }
            return this.academicScaleValueNameMap[data].label;
          }
        }
      },
      onStart: () => {
        setTimeout(() => {
          $('.irs-grid-pol.small').css('height', '0px');
        }, 300);
      },
      onFinish: (data) => {
        if(selectorName == 'impact') {
          this.impactModel[entityId].scale_value_id = data.from;
        } else if (selectorName == 'strand') {
          this.strandModel[entityId].scale_value_id = data.from;
        } else if (selectorName == 'standard') {
          this.standardModel[entityId].scale_value_id = data.from;
        } else if (selectorName == 'course') {
          this.courseModel.scale_value_id = data.from;
        }

        setTimeout(() => {
          this.save(0);
        }, 500);
      },
      // onUpdate: (data) => {
        //   console.log(data.from);
        // }
      });
    }

  ngDoCheck() {
    const studId = this.route.snapshot.paramMap.get('student_id');
    const secId = this.route.parent.snapshot.paramMap.get('section_id');
    if ((studId && this.studentId !== studId)) {
      console.log('In if condition of student ndDocheck');
      console.log(studId);
      this.studentId = studId;
      this.studentServices = [];
      this.getStudentReport();
      this.getCourseLinks();
      this.impactScaleValueCount = 0;
      this.academicScaleValueCount = 0;
      this.getCheckedSpecialServicesData();
      this.getReflections();
    }
    if (secId && this.sectionId != secId){
      console.log('In if condition of section');
      console.log(secId);
      this.sectionId = secId;
      this.studentServices = [];
      this.getStudentReport();
      this.getCourseLinks();
      this.impactScaleValueCount = 0;
      this.academicScaleValueCount = 0;
      this.getCheckedSpecialServicesData();
      this.getReflections();
    }

  }

  getStudentReport() {
    this.studentServices = [];
    this.teachers.getStudentReportSettings(
      this.sectionId,
      this.studentId
    )
    .subscribe(response => {

      const data: any = response;
      this.student = data.student;
      this.impactScale = data.impactScale;
      this.academicScale = data.academicScale;
      this.impacts = data.reportTemplateImpacts;
      this.strands = data.reportTemplateStrands;
      this.reportSettings = data.reportTemplateSettings;
      data.service_types.forEach(element => {
        this.studentServices.push({id: element.id, text: element.name});
      });

      this.findingDefaultScaleValue(this.impactScale.scale_values, this.academicScale.scale_values);
      this.mappingImpactData();
      this.mappingStrandData();

      if (this.reportSettings || this.reportSettings.course_status || this.reportSettings.course_comment_status) {
        this.setCourseData();
      }

      if (this.impactScale) {
        this.scaleValueNameMapping(this.impactScale, 'impact');
      }

      if (this.academicScale) {
        this.scaleValueNameMapping(this.academicScale, 'academic');
      }
    });
  }

  setCourseData() {

    this.courseModel = {
                          report_tempate_id: this.reportSettings.report_template_id, 
                          course_id: this.reportSettings.course_id,
                          is_completed: 0
                        }

    if (this.reportSettings.report_template.report_template_course_scores &&
        this.reportSettings.report_template.report_template_course_scores.length != 0) {

      if (this.reportSettings.course_scale_status) {
        if (this.reportSettings.report_template.report_template_course_scores[0] &&
              this.reportSettings.report_template.report_template_course_scores[0].scale_value_id) {

            this.courseModel.scale_value_id = this.reportSettings.report_template.report_template_course_scores[0].scale_value_id;
        }else {
            this.courseModel.scale_value_id = this.defaultAcademicScaleValue;
        }

        setTimeout(() => {
          this.slider(
                      'course',
                      '',
                      this.academicScale.scale_values[2].id,
                      this.academicScale.scale_values[this.academicScale.scale_values.length - 1].id,
                      this.courseModel.scale_value_id,
                      this.academicScaleValueCount - 3
                    );
          }, 500);
      }

      if (this.reportSettings.report_template.report_template_course_scores[0] &&
            this.reportSettings.report_template.report_template_course_scores[0].comment) {

        this.courseModel.comment = this.reportSettings.report_template.report_template_course_scores[0].comment;
      }
    }else {
      if (this.reportSettings.course_scale_status) {
        this.courseModel.scale_value_id = this.defaultAcademicScaleValue;
        setTimeout(() => {
          this.slider(
                      'course',
                      '',
                      this.academicScale.scale_values[2].id,
                      this.academicScale.scale_values[this.academicScale.scale_values.length - 1].id,
                      this.courseModel.scale_value_id,
                      this.academicScaleValueCount - 3
                    );
          }, 500);
      }
    }
  }

  findingDefaultScaleValue(impact_scale_values, academic_scale_values) {

    impact_scale_values.forEach(element => {
      if (element.is_default) {
        this.defaultImpactScaleValue = element.id;
      }
    });

    academic_scale_values.forEach(element => {
      if (element.is_default) {
        this.defaultAcademicScaleValue = element.id;
      }
    });
  }

  mappingImpactData() {

    this.impacts.forEach(element => {

      // Setting scale value if already present..
      this.impactModel[element.id] = { report_template_impact_id: element.id};
      if (element.report_template_impact_scores.length !== 0) {
        // if (element.report_template_impact_scores[0].scale_value_id === 1) {
        //   this.impactModel[element.id].scale_value_id = true;
        // } else {
          this.impactModel[element.id].scale_value_id = element.report_template_impact_scores[0].scale_value_id;
        // }

        // setting comment if already present
        if (element.report_template_impact_scores[0].comment) {
          this.impactModel[element.id].comment = element.report_template_impact_scores[0].comment;
        }else {
          this.impactModel[element.id].comment = '';
        }

      }else {
        this.impactModel[element.id].scale_value_id = this.defaultImpactScaleValue;
        this.impactModel[element.id].comment = '';
      }

      setTimeout(() => {
        this.slider(
          'impact',
          element.id,
          this.impactScale.scale_values[2].id,
          this.impactScale.scale_values[this.impactScale.scale_values.length - 1].id,
          this.impactModel[element.id].scale_value_id,
          this.impactScaleValueCount - 3
        );
      }, 500);
    });

  }

  mappingStrandData() {

    this.strands.forEach(element => {

      // check if strand is in report tempate strand, only then create its model
      if (typeof element.report_template_strand_scores !== 'undefined') {

        this.strandModel[element.id] = { report_template_strand_id: element.id};
        // Setting scale value if already present..
        if (element.report_template_strand_scores.length !== 0) {
          // if (element.report_template_strand_scores[0].scale_value_id === 1) {
          //   this.strandModel[element.id].scale_value_id = true;
          // } else {
            this.strandModel[element.id].scale_value_id = element.report_template_strand_scores[0].scale_value_id;
          // }

          // setting comment if already present
          if (element.report_template_strand_scores[0].comment) {
            this.strandModel[element.id].comment = element.report_template_strand_scores[0].comment;
          } else {
            this.strandModel[element.id].comment = '';
          }

        } else {
          this.strandModel[element.id].scale_value_id = this.defaultAcademicScaleValue;
          this.strandModel[element.id].comment = '';
        }

        setTimeout(() => {

          this.slider(
                      'strand',
                      element.id,
                      this.academicScale.scale_values[2].id,
                      this.academicScale.scale_values[this.academicScale.scale_values.length - 1].id,
                      this.strandModel[element.id].scale_value_id,
                      this.academicScaleValueCount - 3
                    );
        }, 500);
      }

      // Same thing for standard
      if (element.report_standards) {
        this.mappingStandardsData(element);
      }
    });

  }

  mappingStandardsData(strand) {

    strand.report_standards.forEach(element => {

      this.standardModel[element.id] = {report_template_standard_id: element.id};
      // Setting scale value if already present..
      if (element.report_template_standard_scores.length !== 0) {
        // if (element.report_template_standard_scores[0].scale_value_id === 1) {
        //   this.standardModel[element.id].scale_value_id = true;
        // } else {
          this.standardModel[element.id].scale_value_id = element.report_template_standard_scores[0].scale_value_id;
        // }

        // setting comment if already present
        if (element.report_template_standard_scores[0].comment) {
          this.standardModel[element.id].comment = element.report_template_standard_scores[0].comment;
        } else {
          this.standardModel[element.id].comment = '';
        }

      } else {
        this.standardModel[element.id].scale_value_id = this.defaultAcademicScaleValue;
        this.standardModel[element.id].comment = '';
      }

      setTimeout(() => {

        this.slider(
                'standard',
                element.id,
                this.academicScale.scale_values[2].id,
                this.academicScale.scale_values[this.academicScale.scale_values.length - 1].id,
                this.standardModel[element.id].scale_value_id,
                this.academicScaleValueCount - 3
              );
      }, 500);

    });
  }

  scaleValueNameMapping(scale, scaleName) {

    if (scaleName == 'impact') {
      scale.scale_values.forEach(element => {
        this.impactScaleValueNameMap[element.id] = {};
        this.impactScaleValueNameMap[element.id].name = element.name;
        if (element.label !== null){
          this.impactScaleValueNameMap[element.id].label = element.label;
        }
        this.impactScaleValueNameMap[element.id].color = element.color;

        this.impactScaleValueCount ++;
      });
    }

    if (scaleName == 'academic') {
      scale.scale_values.forEach(element => {
        this.academicScaleValueNameMap[element.id] = {};
        this.academicScaleValueNameMap[element.id].name = element.name;
        if (element.label !== null) {
          this.academicScaleValueNameMap[element.id].label = element.label;
        }
        this.academicScaleValueNameMap[element.id].color = element.color;

        this.academicScaleValueCount ++;
      });
    }
  }

  checkboxChange(model, id, event) {

    if (model == 'strand') {
      if (event) {
        this.strandModel[id].scale_value_id = event;
      } else {
        this.strandModel[id].scale_value_id = this.defaultAcademicScaleValue;
      }
    } else if (model == 'standard') {
      if (event) {
        this.standardModel[id].scale_value_id = event;
      } else {
        this.standardModel[id].scale_value_id = this.defaultAcademicScaleValue;
      }
    } else if (model == 'impact') {
      if (event) {
        this.impactModel[id].scale_value_id = event;
      } else {
        this.impactModel[id].scale_value_id = this.defaultImpactScaleValue;
      }
    }

  }

  save(markingComplete) {

    if (markingComplete) {
      this.courseModel.is_completed = 1;
    }else {
      this.courseModel.is_completed = 0;
    }

    const data = {
      student_id: this.studentId,
      report_template_course_score: this.courseModel,
      report_template_standard_scores: this.standardModel,
      report_template_impact_scores: this.impactModel,
      report_template_strand_scores: this.strandModel
    }

    this.teachers.saveStudentReport(data).subscribe(response => {
      this.toastr.success('Report saved successfully', 'Success!');
      const res: any = response;
      if (res.reportTemplateCourseScores && res.reportTemplateCourseScores.is_completed) {
        this.teachers.markComplete(data.student_id);
        this.reportSettings.report_template.report_template_course_scores[0] = {};
        this.reportSettings.report_template.report_template_course_scores[0].is_completed = 1;

      } else {
        this.teachers.markComplete(data.student_id, 1);
      }
    }, error => {
      this.toastr.error('Report could not be saved!', 'Error!');
    });
  }

  getReflections() {
    this.savedReflectionId = '';
    this.teacherComment = '';
    this.studentComment = '';
    this.teachers.getReportReflections(this.studentId).subscribe((res) => {
      let data;
      data = res;
      if(data.response && typeof data.response != 'undefined') {
        this.savedReflectionId = data.response.id;
        this.teacherComment = data.response.teacher_comment;
        this.studentComment = data.response.student_comment;
      }

    }, (error) => {
      console.warn(error);
    });
  }

  saveReflection() {
    let data;
    if (!this.savedReflectionId) {
      data = {
        section_id : this.sectionId,
        student_id : this.studentId,
        teacher_comment: this.teacherComment,
        student_comment: this.studentComment
      }
      console.log('In add reflection');
      this.teachers.saveReportReflections(data).subscribe((res) => {
        this.toastr.success('Reflection Saved!', 'Success!');
        let data;
        data = res;
        this.savedReflectionId = data.response.id;
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }else {
      data = {
        teacher_comment: this.teacherComment,
        student_comment: this.studentComment
      }
      console.log('In edit reflection');
      this.teachers.editReportReflections(this.savedReflectionId,data).subscribe((res) => {
        this.toastr.success('Reflection Saved!', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }

  }

  getCheckedSpecialServicesData() {
    this.checkedService = [];
    this.teachers.getReportSpecialServices(
      this.studentId)
      .subscribe((res) => {
        let data;
        data = res;
        if(data.response && typeof data.response != 'undefined') {
          this.checkedService = data.response;
        }
      }, (error) => {
        console.warn(error);
      });
  }

  checked(e, id) {
    let data;
    if (e.target.checked) {
      data =  {
        special_service_type_id : id,
        section_id: this.sectionId,
        student_id: this.studentId
      }
      this.teachers.saveReportSpecialServices(data).subscribe((res) => {
        this.toastr.success('Service Saved!', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
    if (!e.target.checked) {
      data = {
        special_service_type_id: id
      }
      this.teachers.deleteSpecialService(this.studentId, data).subscribe((res) => {
        this.toastr.success('Service Removed!', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
  }

  checkSelectedNode(node) {
    for (let x in this.checkedService) {
      if (this.checkedService[x].special_service_type_id == node.id) {
        return true;
      }
    }
  }

  getCourseLinks() {
    this.courseLinks = [];
    this.teachers.getCourseLinks(
      this.studentId, this.sectionId)
      .subscribe((res) => {
        let data;
        data = res;
        this.courseLinks = data.course_link;
      }, (error) => {
        console.warn(error);
      });

  }

}
