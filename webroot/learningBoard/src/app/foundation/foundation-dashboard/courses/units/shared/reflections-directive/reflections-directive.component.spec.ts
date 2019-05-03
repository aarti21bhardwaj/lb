import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ReflectionsDirectiveComponent } from './reflections-directive.component';

describe('ReflectionsDirectiveComponent', () => {
  let component: ReflectionsDirectiveComponent;
  let fixture: ComponentFixture<ReflectionsDirectiveComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ReflectionsDirectiveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ReflectionsDirectiveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
