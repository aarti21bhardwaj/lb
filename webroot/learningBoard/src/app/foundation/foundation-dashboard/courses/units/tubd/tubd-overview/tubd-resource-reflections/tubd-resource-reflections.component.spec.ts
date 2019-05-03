import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TubdResourceReflectionsComponent } from './tubd-resource-reflections.component';

describe('TubdResourceReflectionsComponent', () => {
  let component: TubdResourceReflectionsComponent;
  let fixture: ComponentFixture<TubdResourceReflectionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TubdResourceReflectionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TubdResourceReflectionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
