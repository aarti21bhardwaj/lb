import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TubdComponent } from './tubd.component';

describe('TubdComponent', () => {
  let component: TubdComponent;
  let fixture: ComponentFixture<TubdComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TubdComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TubdComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
