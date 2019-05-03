import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WeKnowComponent } from './we-know.component';

describe('WeKnowComponent', () => {
  let component: WeKnowComponent;
  let fixture: ComponentFixture<WeKnowComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WeKnowComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WeKnowComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
