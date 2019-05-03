import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ReflectionsComponent } from './reflections.component';

describe('ReflectionsComponent', () => {
  let component: ReflectionsComponent;
  let fixture: ComponentFixture<ReflectionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ReflectionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ReflectionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
