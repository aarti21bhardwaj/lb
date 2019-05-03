import { Component, OnInit } from '@angular/core';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationExtras, UrlSegment } from '@angular/router';
import { UsersService } from '../services/users/users.service';
import { FeedbackService } from '../services/feedback/feedback.service';

@Component({
  selector: 'app-feedback-dashboard',
  templateUrl: './feedback-dashboard.component.html',
  styleUrls: ['./feedback-dashboard.component.scss']
})
export class FeedbackDashboardComponent implements OnInit {

  courseId: any;
  sectionId: string;
  courses: any = [];
  evaluations: any = [];
  filter_name: any;
  selected_section_id: any;
  showAssessmentTypes:any={};
  keys:any=[];
  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private userService: UsersService,
    private feedbackService: FeedbackService
  ) {
    this.userService.getUserDetails().subscribe(res => {
      this.courses = res;
      this.courses = this.courses.data.courseData
    });
  }

  ngOnInit() {
  }

  ngDoCheck() {
    const secId = this.route.snapshot.paramMap.get('section_id');
    if (secId && this.sectionId !== secId) {
      this.sectionId = secId;
      this.sectionSelected(this.sectionId);
    }
  }

  sectionSelected(sectionId) {
    this.selected_section_id = sectionId;
    this.feedbackService.getEvaluations(sectionId).subscribe((res) => {
      this.evaluations = res;
      this.evaluations = this.evaluations.data;
      if(this.evaluations != null && typeof this.evaluations != 'undefined' && this.evaluations.length>0) {
        console.log('in if');
        console.log(this.evaluations);
        this.courseId = this.evaluations[0].section.course_id;
        this.evaluations.forEach(evaluation => {
            let x :any;
            x = {
              'id' : evaluation.assessment.assessment_type.id,
              'name':evaluation.assessment.assessment_type.name,
              'color':evaluation.assessment.assessment_type.color
            }
              if(!(evaluation.assessment.assessment_type.id in this.showAssessmentTypes)){

                this.showAssessmentTypes[evaluation.assessment.assessment_type.id]= x;
              }
              
        });
        this.keys=Object.keys(this.showAssessmentTypes);
      }

    });
  }

  archiveEvaluation(evaluationId) {
    this.feedbackService.updateArchievedStatus(evaluationId).subscribe( (res) => {
      this.sectionSelected(this.selected_section_id);
    });
  }
}
