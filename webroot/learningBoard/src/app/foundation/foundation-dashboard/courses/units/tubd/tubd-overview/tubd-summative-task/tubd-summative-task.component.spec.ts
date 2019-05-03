import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TubdSummativeTaskComponent } from './tubd-summative-task.component';

describe('TubdSummativeTaskComponent', () => {
  let component: TubdSummativeTaskComponent;
  let fixture: ComponentFixture<TubdSummativeTaskComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TubdSummativeTaskComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TubdSummativeTaskComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
