import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StandardsPolarComponent } from './standards-polar.component';

describe('StandardsPolarComponent', () => {
  let component: StandardsPolarComponent;
  let fixture: ComponentFixture<StandardsPolarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StandardsPolarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StandardsPolarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
