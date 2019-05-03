import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FoundationDashboardComponent } from './foundation-dashboard.component';

describe('FoundationDashboardComponent', () => {
  let component: FoundationDashboardComponent;
  let fixture: ComponentFixture<FoundationDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FoundationDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FoundationDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
