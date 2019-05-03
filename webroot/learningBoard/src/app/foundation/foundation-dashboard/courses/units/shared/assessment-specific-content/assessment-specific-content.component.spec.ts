import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AssessmentSpecificContentComponent } from './assessment-specific-content.component';

describe('AssessmentSpecificContentComponent', () => {
  let component: AssessmentSpecificContentComponent;
  let fixture: ComponentFixture<AssessmentSpecificContentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AssessmentSpecificContentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AssessmentSpecificContentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
