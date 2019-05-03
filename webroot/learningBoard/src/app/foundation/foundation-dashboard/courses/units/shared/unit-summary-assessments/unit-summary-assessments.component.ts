import { Component, OnInit, Input } from '@angular/core';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';

@Component({
  selector: 'app-unit-summary-assessments',
  templateUrl: './unit-summary-assessments.component.html',
  styleUrls: ['./unit-summary-assessments.component.scss']
})
export class UnitSummaryAssessmentsComponent implements OnInit {
  @Input() courseId: any;
  @Input() unitId: any;
  @Input() categoryId: any;
  @Input() title: any;
  @Input() type: any;
  @Input() isExpanded = false;
  summativeTaskList:any;
  learningExperienceTasklist:any;

  constructor(private performanceService: PerformanceTaskService) { }

  ngOnInit() {
    console.log('inside UnitSummary Assessments');
    if (this.courseId && this.unitId) {
      if (this.type == 'SummativeTasks') {
        this.getSummativeTaskList();
      }
      if (this.type == 'LearningExperiences') {
        this.getLearningExperiences();
      }
      
    }
  }
  getSummativeTaskList(){
    
    this.performanceService.getTaskList(
                this.courseId,this.unitId,2)
      .subscribe((response) => {
        this.summativeTaskList = response;
        this.summativeTaskList = this.summativeTaskList.data;
        console.log(this.summativeTaskList);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
        console.warn(error);
      });
  }
  getLearningExperiences(){
    this.performanceService.getTaskList(
                this.courseId,this.unitId,4)
      .subscribe((response) => {
        this.learningExperienceTasklist = response;
        this.learningExperienceTasklist = this.learningExperienceTasklist.data;
        console.log(this.learningExperienceTasklist);
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      }, (error) => {
        console.warn(error);
      });
  }
}
