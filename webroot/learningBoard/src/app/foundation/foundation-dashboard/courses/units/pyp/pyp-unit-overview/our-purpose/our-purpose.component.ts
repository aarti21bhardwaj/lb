import { OnInit, TemplateRef, Component, ViewContainerRef } from '@angular/core';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { UnitsService } from '../../../../../../../services/foundation/units/units.service';
import { CoursesService } from './../../../../../../../services/foundation/courses/courses.service';
import { TeacherService } from 'app/services/foundation/teachers/teacher.service';
import { UnitcontentComponent } from '../../../shared/unitcontent/unitcontent.component';
import { ActivatedRoute } from '@angular/router';
import { PypComponent } from '../../../pyp/pyp.component';


declare var tinymce: any;

@Component({
  selector: 'app-our-purpose',
  templateUrl: './our-purpose.component.html',
  styleUrls: ['./our-purpose.component.scss']
})
export class OurPurposeComponent implements  OnInit {
  transdisciplinaryId: any;
  transdisciplinarySkills: any;
  specificLineOfInq: any;
  lineOfInqId: any;
  commonLineOfInq: any;
  relatedConceptId: any;
  centralIdeaId: any;
  spinnerEnabled = true;
  unitId: any;
  courseId: any;
  courseDetails: any;
  centralIdea: any = false;
  unitData: any;
  keyConcepts: any;
  relatedConcepts: any = false;
  learnerProfiles: any;
  unitCheckedKeyConcept: any;
  unitCheckedLearnerProfiles: any;
  centralIdeaDes: any = false;
  relatedConceptsDes: any = false;
  showEditRelated = false;
  showEditCentral = false;
  showAddRelated = true;
  showAddCentral = true;

  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough',
      'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color',
      // 'inlineStyle', 'paragraphStyle',
      // 'paragraphFormat',
      'align',
      'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
      'insertLink', 'insertImage', 'insertVideo', 'embedly',
      // 'insertFile',
      'insertTable', '|',
      // 'emoticons', 
      'specialCharacters',
      // 'insertHR',
      'selectAll', 'clearFormatting', '|',
      // 'print', 
      'spellChecker',
      // 'help', 
      // 'html', '|', 
      'undo', 'redo']
  }

  constructor(private unitService: UnitsService,
    private courseService: CoursesService,
    private teacherService: TeacherService,
    private acivatedRoute: ActivatedRoute,
    public toastr: ToastsManager,
    vcr: ViewContainerRef, private parent: PypComponent) {
    this.toastr.setRootViewContainerRef(vcr);
    this.acivatedRoute.parent.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    parent.isMetaUnitActive = false;
   }

  ngOnInit() {
    /* Unit service data */
    this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
      this.unitData = res;
      this.unitData = this.unitData.unit;
      this.spinnerEnabled = false;
    }, (error) => console.warn('Error in getting course' + error)
    );

    /* Getting course by course Id */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        let res;
        res = response;
        if(res) {
          this.courseDetails = res.data;   
          this.courseDetails.content_categories.forEach(element => {
            if (element.type === 'central_idea') {
              console.log('In if when both types are equal central idea');
              let idea: any;
              idea = element;
              this.centralIdeaId = idea.id;
  
              /* find Checked commin content */
              // this.checked = this.unitData.unit_contents[this.categoryId].content_categories.content_values;
              this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.centralIdeaId).subscribe((res) => {
                let response;
                response = res;
                if(response) {
                  this.centralIdea = response.response.data.unit_specific_contents[0];
                    if (this.centralIdea) {
                      this.showAddCentral = false;
                      this.showEditCentral = true;
                      this.centralIdeaDes = this.centralIdea.text;
                  }
                  console.log('unt specific data' + this.centralIdea);
                }
              }, (error) => {
                console.warn('Error in adding unit content' + error);
              });
            }
            if (element.type === 'key_concepts') {
              console.log('In if when both types are equal key concepts');
              this.keyConcepts = element;
  
              this.unitService.getUnitContent(
                this.courseId, this.unitId, this.keyConcepts.id)
                .subscribe((res) => {
                  let response;
                  response = res;
                  if(response) {
                    this.unitCheckedKeyConcept = response.response.data.unit_contents;
                  }
                }, (error) => {
                  console.warn(error);
                });
  
              /* find Checked commin content */
              // this.checked = this.unitData.unit_contents[this.categoryId].content_categories.content_values;
            }
            if (element.type === 'related_concepts') {
              console.log('In if when both types are equal related concept');
              let concept: any;
              concept = element;
              this.relatedConceptId = concept.id;
  
              /* find Checked commin content */
              // this.checked = this.unitData.unit_contents[this.categoryId].content_categories.content_values;
              this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.relatedConceptId).subscribe((res) => {
                let data;
                data = res;
                if(data && typeof data != 'undefined') {
                  this.relatedConcepts = data.response.data.unit_specific_contents[0];
                    if (this.relatedConcepts) {
                      this.showAddRelated = false;
                      this.showEditRelated = true;
                      this.relatedConceptsDes = this.relatedConcepts.text;
                  }
                  console.log('unt specific data' + this.relatedConcepts);
                }
              }, (error) => {
                console.warn('Error in adding unit content' + error);
              });
            }
            if (element.type === 'learner_profile') {
              console.log('In if when both types are equal learner profile');
              this.learnerProfiles = element;
  
              this.unitService.getUnitContent(
                this.courseId, this.unitId, this.learnerProfiles.id)
                .subscribe((res) => {
                  this.unitCheckedLearnerProfiles = res;
                  if (this.unitCheckedLearnerProfiles) {
                    this.unitCheckedLearnerProfiles = this.unitCheckedLearnerProfiles.response.data.unit_contents;
                  }
                }, (error) => {
                  console.warn(error);
                });
            }
            if (element.type === 'lines_of_inquiry') {
              console.log('In if when both types are equal line of inquiry');
              this.commonLineOfInq = element;
              this.lineOfInqId = this.commonLineOfInq.id;
  
              /* find Checked commin content */
              // this.checked = this.unitData.unit_contents[this.categoryId].content_categories.content_values;
              this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.lineOfInqId).subscribe((res) => {
                let data: any;
                data = res;
                data = data.response.data;
                if (data) {
                  this.specificLineOfInq = this.relatedConcepts.response.data.unit_specific_contents;
                }
              }, (error) => {
                console.warn('Error in adding unit content' + error);
              });
            }
            if (element.type === 'transdisciplinary_skills') {
              console.log('In if when both types are equal trans skills');
              this.transdisciplinarySkills = element;
              this.transdisciplinaryId = this.transdisciplinarySkills.id;
            }
          })
        }
      }, (error) => {
        console.warn(error);
      });

  }

  checkSelectedNode(node, type) {
    if (type === 'concept') {
      for (let x in this.unitCheckedKeyConcept) {
        if (this.unitCheckedKeyConcept[x].content_value_id == node.id) {
          return true;
        }
      }
    }
    if (type === 'profile') {
      for (let x in this.unitCheckedLearnerProfiles) {
        if (this.unitCheckedLearnerProfiles[x].content_value_id == node.id) {
          return true;
        }
      }
   }
  }

  checked(e, id, type) {
    let categoryId: any;
    if (type === 'concept') {
      categoryId = this.keyConcepts.id;
      console.log('any event happend' + e.target);
      if (e.target.checked) {
        console.log('when checked box is checked' + id);
        this.unitService.addUnitcontent(this.courseId, this.unitId, categoryId, id).subscribe((res) => {
          console.log(res);
          this.courseService.refreshUnitSummary();
          this.toastr.success('Saved!', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
      }
      if (!e.target.checked) {
        console.log('when checked box is unchecked' + id);
        this.unitService.deleteUnitContent(this.courseId, this.unitId, categoryId, id).subscribe((res) => {
          console.log(res);
          this.courseService.refreshUnitSummary();
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
      }
    }
    if (type === 'profile') {
     categoryId = this.learnerProfiles.id;
      console.log('any event happend' + e.target);
      if (e.target.checked) {
        console.log('when checked box is checked' + id);
        this.unitService.addUnitcontent(this.courseId, this.unitId, categoryId, id).subscribe((res) => {
          console.log(res);
          this.courseService.refreshUnitSummary();
          this.toastr.success('Saved!', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
      }
      if (!e.target.checked) {
        console.log('when checked box is unchecked' + id);
        this.unitService.deleteUnitContent(this.courseId, this.unitId, categoryId, id).subscribe((res) => {
          console.log(res);
          this.courseService.refreshUnitSummary();
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
      }
    } 
  }

  addSpecificContent(type) {
    console.log('In add case' + type);
    let categoryId: any;
    let description: any;

    if (type == 'idea') {
      categoryId = this.centralIdeaId;
      description = this.centralIdeaDes;
    }
    if (type == 'concept') {
      categoryId = this.relatedConceptId;
      description = this.relatedConceptsDes;
    }
    console.log('description' + description);
    this.unitService.addUnitSpecificContent(this.courseId, this.unitId, categoryId, description).subscribe((res) => {
      console.log('Response after adding unit specific content' + res);
      this.toastr.success('Saved!', 'Success!');
      this.courseService.refreshUnitSummary();
      if (type == 'idea') {
       this.showAddCentral = false;
       this.showEditCentral = true;
      }
      if (type == 'concept') {
        this.showAddRelated = false;
        this.showEditRelated = true;
      }
    }, (error) => {
      this.toastr.error(error, 'Error!');
      console.warn('Error in adding unit content' + error);
    });
  }

  editSpecificContent(type) {
    console.log('In edit case' + type);
    let categoryId: any;
    let description: any;
    let contentId: any;

    if (type == 'idea') {
      categoryId = this.centralIdeaId;
      description = this.centralIdeaDes;
      contentId = this.centralIdea.id;
    }
    if (type == 'concept') {
      categoryId = this.relatedConceptId;
      description = this.relatedConceptsDes;
      contentId = this.relatedConcepts.id;
    }

    console.log('description that is going to add' + description);
    this.unitService.editUnitSpecificContent(this.courseId, this.unitId, categoryId, description, contentId)
      .subscribe((res) => {
        console.log('Response after editing unit specific content' + res);
        this.courseService.refreshUnitSummary();
        this.toastr.success('Saved!', 'Success!');
        if (type == 'idea') {
          this.showAddCentral = false;
          this.showEditCentral = true;
        }
        if (type == 'concept') {
          this.showAddRelated = false;
          this.showEditRelated = true;
        }
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn('Error in editing unit content' + error);
      });
  }
 
}
