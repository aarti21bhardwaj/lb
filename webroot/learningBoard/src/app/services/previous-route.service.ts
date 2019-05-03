import { Injectable } from '@angular/core';
import { Router, NavigationEnd, NavigationStart  } from '@angular/router';

@Injectable()
export class PreviousRouteService {

  private previousUrl: string;
  private currentUrl: string;

  constructor(private router: Router) {
    this.currentUrl = this.router.url;
    router.events.subscribe(event => {
      console.log('Event before instance');
      console.log(event);
      if (event instanceof NavigationEnd) {
        this.previousUrl = this.currentUrl;
        console.log('previous url in service');
        console.log(this.previousUrl);
        this.currentUrl = event.url;
        console.log(this.currentUrl);
      };
    });
  }

  public getPreviousUrl() {
    return this.previousUrl;
  }
}