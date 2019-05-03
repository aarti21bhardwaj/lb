import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UbdComponent } from './ubd.component';

describe('UbdComponent', () => {
  let component: UbdComponent;
  let fixture: ComponentFixture<UbdComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UbdComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UbdComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
