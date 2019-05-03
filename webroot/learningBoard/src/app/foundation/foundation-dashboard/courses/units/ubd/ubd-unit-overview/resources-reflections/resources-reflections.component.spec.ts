import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ResourcesReflectionsComponent } from './resources-reflections.component';

describe('ResourcesReflectionsComponent', () => {
  let component: ResourcesReflectionsComponent;
  let fixture: ComponentFixture<ResourcesReflectionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ResourcesReflectionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ResourcesReflectionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
