import { Component, OnInit, ViewChild } from '@angular/core';
import { CalendarComponent as Cal} from 'ng-fullcalendar';
import { Options } from 'fullcalendar';
import { ActivatedRoute,Router } from '@angular/router';
import { UnitsService } from './../../../services/foundation/units/units.service';
import { CoursesService } from './../../../services/foundation/courses/courses.service';
@Component({
  selector: 'app-calendar',
  templateUrl: './calendar.component.html',
  styleUrls: ['./calendar.component.scss'],
})

export class CalendarComponent implements OnInit {
  sectionId:number;
  courseId:number;
  dateObj = new Date();
  courseDetails:any;
  spinnerEnabled: boolean = false;
  eventList: any ;
  students:any;
  sectionDetails: any;
  calendarOptions: Options;
  @ViewChild(CalendarComponent) ucCalendar: Cal;
  
  constructor( private acivatedRoute: ActivatedRoute,
    private router: Router,
    private unitService: UnitsService,
    private courseService: CoursesService) {}
  
  ngOnInit() {
    this.acivatedRoute.params.subscribe(res => {
        console.log(res);
        this.sectionId = res.section_id;
        this.courseId = res.course_id;
        this.fetchSectionEvents(res.section_id);
        this.getCourseDetails();
      });
     

  }
  fetchSectionEvents(sectionId){
    this.spinnerEnabled = true;
    this.courseService.getSectionStudents(sectionId)
    .subscribe((response) => {
      this.students = response;
      this.students = this.students.data;
    }, (error) => {
      console.warn(error);
    });
    this.unitService.getSectionEvent(sectionId)
      .subscribe((response) => {
        this.eventList = response;
        this.eventList = this.eventList.data;
        this.calendarOptions = {
            editable: true,
            eventLimit: false,
            header: {
              left: 'prev,next today',
              center: 'title',
              right: 'month,listMonth'
            },
            events: this.eventList,
            displayEventTime:false
          };
        // this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/transfer/' + this.savedUnit.response.data.id);
      this.spinnerEnabled = false;
      }, (error) => {
        console.warn(error);
        this.spinnerEnabled = false;
      });
}

  eventClick(event) {
    console.log('In event click' + event);
    console.log('object name in event'  + event.event.object_name);
    console.log('object identifier in event' + event.event.object_identifier);
    if(event.event.object_name == 'evaluation'){
      this.router.navigateByUrl('/feedback/assessment/' + event.event.object_identifier);
    }
    if (event.event.object_name == 'evaluation') {
      this.router.navigateByUrl('/feedback/assessment/' + event.event.object_identifier);
    }
    if (event.event.object_name == 'unit') {
      this.router.navigateByUrl('/foundation/courses/' + event.event.section.course_id + '/units/' + event.event.template_name
      + '/' + event.event.object_identifier);
    }
  }

  getCourseDetails() {
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.sections.forEach(section => {
          if (section.id == this.sectionId) {
            this.sectionDetails = section;
          }
        })
      }, (error) => {
        console.warn(error);
      });

  }

}