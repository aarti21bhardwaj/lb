import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImpactsAddTreeComponent } from './impacts-add-tree.component';

describe('ImpactsAddTreeComponent', () => {
  let component: ImpactsAddTreeComponent;
  let fixture: ComponentFixture<ImpactsAddTreeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImpactsAddTreeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImpactsAddTreeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
