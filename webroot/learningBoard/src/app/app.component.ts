import { Component, TemplateRef } from '@angular/core';
import { UsersService } from './services/users/users.service';
import { ActivatedRoute, Router, UrlSegment, NavigationEnd} from '@angular/router';
import { NgSwitchCase } from '@angular/common/src/directives';
import { AppSettings } from './app-settings';
import { ModalModule } from 'ngx-bootstrap/modal';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { ViewChild, ElementRef, AfterViewInit } from '@angular/core'


@Component({
  // tslint:disable-next-line
  selector: 'body',
  templateUrl: 'app.component.html'
})
export class AppComponent {
  modalRef: BsModalRef;
  public disabled = false;
  public selectedTopNavOption = null;
  public status: {isopen: boolean} = {isopen: false};
  public user_info: any;
  public selectedOption;
  selected = 'option2';
  public logoutUrl: any;
  public backToAdmin: any;
  confirmNewPassword: any;
  firstInitAfterLogin: boolean;
  custom_links = {};

  constructor(
    public user: UsersService,
    private router: Router,
    private route: ActivatedRoute,
    private modalService: BsModalService,
  ) {
    this.custom_links = AppSettings.CUSTOM_LINKS;
    this.backToAdmin = AppSettings.ENVIRONMENT + 'schools';
    this.logoutUrl = AppSettings.ENVIRONMENT + 'users/logout';
    router.events.filter(event => event instanceof NavigationEnd)
        .subscribe(event => {
          let urlData:any;
            urlData = event;
            urlData = urlData.url;
            urlData =urlData.split("/");
            console.log(urlData);
            if(urlData[1] == "teaching-hub"){
              this.selectedTopNavOption ="TEACHING HUB";
              this.firstInitAfterLogin = false;
            }else if(urlData[1] == "analytics"){
              this.selectedTopNavOption = "ANALYTICS";
              this.firstInitAfterLogin = false;
            }else if(urlData[1] == "feedback"){
              this.selectedTopNavOption ="FEEDBACK";
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == "foundation"){
              this.selectedTopNavOption = "FOUNDATION";
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == "reports") {
              this.selectedTopNavOption = "REPORTS";
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == 'teacher-evidences') {
              this.selectedTopNavOption = 'EDUCATOR PORTFOLIO';
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == 'unit-browser') {
              this.selectedTopNavOption = 'UNIT BROWSER';
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == 'student-dashboard') {
              this.selectedTopNavOption = 'STUDENT HUB';
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == 'evidences') {
              this.selectedTopNavOption = 'EVIDENCE';
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == 'parent-dashboard') {
              this.selectedTopNavOption = 'PARENT HUB';
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == 'circumplex') {
              this.selectedTopNavOption = 'CIRCUMPLEX';
              this.firstInitAfterLogin = false;
            } else if (urlData[1] == 'progress-report') {
              this.selectedTopNavOption = 'PROGRESS REPORT';
              this.firstInitAfterLogin = false;  
            } else {
              this.firstInitAfterLogin = true;
              this.selectedTopNavOption = 'TEACHING HUB';
            }
    });
  }

  ngOnInit() {

    this.user.getUserDetails()
      .subscribe((response) => {
        this.user_info = response;
        this.user_info = this.user_info.data;

        if (this.firstInitAfterLogin) {

          // Checking if logged in user is student. Redirecting the user to student landing page if true.
          if(this.user_info.role == 'Student') {
            this.router.navigate(['/evidences']);
          }
        }
        if (this.firstInitAfterLogin) {

          // Checking if logged in user is guardian. Redirecting the user to student landing page if true.
          if (this.user_info.role == 'Guardian') {
            this.router.navigate(['/parent-dashboard']);
          }
        }

      });
    this.getRoutes();
  }

  logout() {
    window.location.href = this.logoutUrl
  }

  getRoutes() { const segments: UrlSegment[] = this.route.snapshot.url;
  console.log(segments);}

  public goToRoute(route) {

    if(route == 'feedback') {
      this.router.navigate(['/feedback/']);
    } else if (route == 'foundation') {
      this.router.navigate(['/foundation/courses/']);
    } else if (route == 'analytics') {
      this.router.navigate(['/analytics/']);
    } else if (route == 'teaching') {
      this.router.navigate(['/teaching-hub/']);
    } else if (route == 'teacher-evidences') {
      this.router.navigate(['/teacher-evidences/']);
    } else if (route == 'reports') {
      this.router.navigate(['/reports/']);
    } else if (route == 'student-dashboard') {
      this.router.navigate(['/student-dashboard/']);
    } else if (route == 'evidences') {
      this.router.navigate(['/evidences/']);
    } else if (route == 'unit-browser') {
      this.router.navigate(['/unit-browser/']);
    }

  }

  public toggled(open: boolean): void {
    console.log('Dropdown is now: ', open);
  }

  public toggleDropdown($event: MouseEvent): void {
    $event.preventDefault();
    $event.stopPropagation();
    this.status.isopen = !this.status.isopen;
  }

  openModel(template: TemplateRef<any>) {
    console.log('In change password method');
    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
  }

  updatePassword() {
    console.log('In update password');
    this.user.changePassword(this.user_info.userId, this.confirmNewPassword)
      .subscribe((response) => {
        this.user_info = response;
        this.user_info = this.user_info.data;
        this.logout();
      }, (error) => {
        console.warn(error);
      }
    );
  }
}

