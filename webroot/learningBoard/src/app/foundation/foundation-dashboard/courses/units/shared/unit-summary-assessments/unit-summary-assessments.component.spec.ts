import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnitSummaryAssessmentsComponent } from './unit-summary-assessments.component';

describe('UnitSummaryAssessmentsComponent', () => {
  let component: UnitSummaryAssessmentsComponent;
  let fixture: ComponentFixture<UnitSummaryAssessmentsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnitSummaryAssessmentsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnitSummaryAssessmentsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
