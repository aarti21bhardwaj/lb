import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TreeUnitContentComponent } from './tree-unit-content.component';

describe('TreeUnitContentComponent', () => {
  let component: TreeUnitContentComponent;
  let fixture: ComponentFixture<TreeUnitContentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TreeUnitContentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TreeUnitContentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
