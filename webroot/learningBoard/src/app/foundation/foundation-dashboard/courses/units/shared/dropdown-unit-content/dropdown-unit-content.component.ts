import { Component, OnInit, Input, ViewContainerRef } from '@angular/core';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-dropdown-unit-content',
  templateUrl: './dropdown-unit-content.component.html',
  styleUrls: ['./dropdown-unit-content.component.scss']
})
export class DropdownUnitContentComponent implements OnInit {
  commonValueData: any;
  contentId: any = false;
  unitCheckedData: any = [];

  @Input() courseId: any;
  @Input() unitId: any;
  @Input() categoryId: any;
  @Input() commonContents: any;
  @Input() unitSpecificContents: any = [];
  @Input() title: any;
  
  constructor(private unitService: UnitsService, vcr: ViewContainerRef, public toastr: ToastsManager,) { }

  ngOnInit() {
    this.getSelectedData();
  }

  getSelectedData() {
    this.unitService.getUnitContent(
      this.courseId, this.unitId, this.categoryId)
      .subscribe((res) => {
        let data : any ;
        data = res;
        data = data.response.data.unit_contents;
        this.commonValueData = data;
        this.unitCheckedData = [];
        data.forEach(element => {
          this.unitCheckedData.push({ id: element.id, text: element.content_value.text });
        });
      }, (error) => {
        console.warn(error);
      });
  }

  public selected(value: any): void {
    console.log('Selected value is: ', value);
  }
  public removed(value: any): void {
    console.log('Removed value is: ', value);
  }
  public refreshValue(value: any): void {
    console.log('This is the selected value' + value);
    this.contentId = value.id;
  }

  addContent() {
    if (this.unitCheckedData && typeof this.unitCheckedData !== 'undefined' && this.unitCheckedData.length > 0) {
      this.unitService.deleteUnitContent(this.courseId, this.unitId, this.categoryId, this.commonValueData[0].content_value.id)
      .subscribe((res) => {
        this.unitService.addUnitcontent(this.courseId, this.unitId, this.categoryId, this.contentId).subscribe((response) => {
          this.toastr.success('Saved!', 'Success!');
          this.getSelectedData();
        }, (error) => {
          console.warn(error);
        });
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });

    } else {
      this.unitService.addUnitcontent(this.courseId, this.unitId, this.categoryId, this.contentId).subscribe((res) => {
        this.toastr.success('Saved!', 'Success!');
        this.getSelectedData();
      }, (error) => {
        console.warn(error);
      });
    }
  }
}
