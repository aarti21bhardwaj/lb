import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LearningAcivitiesComponent } from './learning-acivities.component';

describe('LearningAcivitiesComponent', () => {
  let component: LearningAcivitiesComponent;
  let fixture: ComponentFixture<LearningAcivitiesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LearningAcivitiesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LearningAcivitiesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
