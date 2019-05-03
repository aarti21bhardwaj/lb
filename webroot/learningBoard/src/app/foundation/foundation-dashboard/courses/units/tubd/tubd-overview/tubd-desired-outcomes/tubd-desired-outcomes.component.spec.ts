import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TubdDesiredOutcomesComponent } from './tubd-desired-outcomes.component';

describe('TubdDesiredOutcomesComponent', () => {
  let component: TubdDesiredOutcomesComponent;
  let fixture: ComponentFixture<TubdDesiredOutcomesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TubdDesiredOutcomesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TubdDesiredOutcomesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
