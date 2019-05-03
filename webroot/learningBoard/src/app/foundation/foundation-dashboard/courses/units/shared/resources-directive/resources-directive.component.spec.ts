import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ResourcesDirectiveComponent } from './resources-directive.component';

describe('ResourcesDirectiveComponent', () => {
  let component: ResourcesDirectiveComponent;
  let fixture: ComponentFixture<ResourcesDirectiveComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ResourcesDirectiveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ResourcesDirectiveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
