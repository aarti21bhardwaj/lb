import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubAssessmentsComponent } from './sub-assessments.component';

describe('SubAssessmentsComponent', () => {
  let component: SubAssessmentsComponent;
  let fixture: ComponentFixture<SubAssessmentsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubAssessmentsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubAssessmentsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
