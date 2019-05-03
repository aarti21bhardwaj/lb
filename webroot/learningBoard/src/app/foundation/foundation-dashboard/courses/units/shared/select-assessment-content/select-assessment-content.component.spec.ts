import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SelectAssessmentContentComponent } from './select-assessment-content.component';

describe('SelectAssessmentContentComponent', () => {
  let component: SelectAssessmentContentComponent;
  let fixture: ComponentFixture<SelectAssessmentContentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SelectAssessmentContentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SelectAssessmentContentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
