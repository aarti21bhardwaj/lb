import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WeLearnComponent } from './we-learn.component';

describe('WeLearnComponent', () => {
  let component: WeLearnComponent;
  let fixture: ComponentFixture<WeLearnComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WeLearnComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WeLearnComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
