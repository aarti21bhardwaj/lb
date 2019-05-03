import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TeachingHubDashboardComponent } from './teaching-hub-dashboard.component';

describe('TeachingHubDashboardComponent', () => {
  let component: TeachingHubDashboardComponent;
  let fixture: ComponentFixture<TeachingHubDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TeachingHubDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TeachingHubDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
