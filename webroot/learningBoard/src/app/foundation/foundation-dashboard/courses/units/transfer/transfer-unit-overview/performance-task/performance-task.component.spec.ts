import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PerformanceTaskComponent } from './performance-task.component';

describe('PerformanceTaskComponent', () => {
  let component: PerformanceTaskComponent;
  let fixture: ComponentFixture<PerformanceTaskComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PerformanceTaskComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PerformanceTaskComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
