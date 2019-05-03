import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LearningExperiencesComponent } from './learning-experiences.component';

describe('LearningExperiencesComponent', () => {
  let component: LearningExperiencesComponent;
  let fixture: ComponentFixture<LearningExperiencesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LearningExperiencesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LearningExperiencesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
