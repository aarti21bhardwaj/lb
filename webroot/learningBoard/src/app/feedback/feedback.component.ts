import { Component, OnInit } from '@angular/core';
import { FeedbackService } from '../services/feedback/feedback.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-feedback',
  templateUrl: './feedback.component.html',
  styleUrls: ['./feedback.component.scss']
})
export class FeedbackComponent implements OnInit {

  assessmentId: any;
  filter_name: any;
  studentSelected: any;
  students: any;
  // val: any = {
  //   "text": "Root node",
  //   "state": { "opened": true },
  //   "children": [
  //     {
  //       "text": "Child node 1",
  //       "state": { "selected": true },
  //       "icon": "glyphicon glyphicon-flash"
  //     },
  //     { "text": "Child node 2", "state": { "disabled": true } }
  //   ]
  // }

  constructor(
    private feedbackService: FeedbackService,
    private route: ActivatedRoute
  ) {
    this.assessmentId = this.route.snapshot.paramMap.get('assessment_id');
    this.feedbackService.getEvaluationStudents(this.assessmentId)
      .subscribe(res => {
        this.students = res;
        this.students = this.students.data;
        this.feedbackService.updateStudents(this.students);
      });
  }

  ngOnInit() {
  }

  ngDoCheck(){
    this.studentSelected = this.route.snapshot.children.length;
  }
}
