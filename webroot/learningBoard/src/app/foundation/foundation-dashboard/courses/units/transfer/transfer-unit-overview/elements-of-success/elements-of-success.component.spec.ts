import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ElementsOfSuccessComponent } from './elements-of-success.component';

describe('ElementsOfSuccessComponent', () => {
  let component: ElementsOfSuccessComponent;
  let fixture: ComponentFixture<ElementsOfSuccessComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ElementsOfSuccessComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ElementsOfSuccessComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
