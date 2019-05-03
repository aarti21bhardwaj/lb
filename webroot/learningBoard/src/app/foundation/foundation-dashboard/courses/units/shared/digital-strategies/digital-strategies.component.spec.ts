import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DigitalStrategiesComponent } from './digital-strategies.component';

describe('DigitalStrategiesComponent', () => {
  let component: DigitalStrategiesComponent;
  let fixture: ComponentFixture<DigitalStrategiesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DigitalStrategiesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DigitalStrategiesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
