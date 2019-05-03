import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TubdLearningExperiencesComponent } from './tubd-learning-experiences.component';

describe('TubdLearningExperiencesComponent', () => {
  let component: TubdLearningExperiencesComponent;
  let fixture: ComponentFixture<TubdLearningExperiencesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TubdLearningExperiencesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TubdLearningExperiencesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
